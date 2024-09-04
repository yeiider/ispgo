<?php

namespace App\Http\Controllers\Api;

use App\Helpers\QrCodeHelper;
use App\Http\Controllers\Controller;
use App\Models\Box;
use App\Models\DailyBox;
use App\Models\Invoice\Invoice;
use App\Settings\GeneralProviderConfig;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    public function search(Request $request): JsonResponse
    {
        // Validar los datos de entrada
        $request->validate([
            'input' => 'required|string'
        ]);

        $input = $request->input('input');

        try {
            $invoiceModel = Invoice::findByDniOrInvoiceId($input);
            $invoice = $this->parseData($invoiceModel);
            if ($invoice) {
                return response()->json(compact('invoice'));
            } else {
                return response()->json(['message' => 'Invoice not found'], 404);
            }
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Invoice not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred while searching for the invoice'], 500);
        }
    }


    private function parseData(Invoice $invoice): array
    {
        return [
            "subtotal" => $invoice->subtotal,
            "increment_id" => $invoice->increment_id,
            "tax" => $invoice->tax,
            "total" => $invoice->total,
            "amount" => $invoice->amount,
            "discount" => $invoice->discount,
            "customer_name" => $invoice->customer->full_name,
            "product" => $invoice->product,
            "status" => $invoice->status,
            "issue_date" => $invoice->issue_date,
            "due_date" => $invoice->due_date,
            "customer" => $invoice->customer,
            "address" => $invoice->service->address
        ];

    }

    public function registerPayment(Request $request): JsonResponse
    {
        $request->validate([
            'paymentReference' => 'required|string',
            'paymentMethod' => 'required|string'
        ]);
        /**
         * @var $invoiceModel Invoice
         **/
        try {
            $reference = $request->input('paymentReference');
            $invoiceModel = Invoice::findByDniOrInvoiceId($reference);
            if ($invoiceModel) {
                $invoiceModel->applyPayment(notes: $request->input('note'), dailyBoxId: $request->input('todaytBox')['id']);
                DailyBox::updateAmount($request->input('todaytBox')['id'], $invoiceModel->amount);
                return response()->json(['message' => 'Payment registered successfully', 'data' => $invoiceModel, 'status' => 200]);
            } else {
                return response()->json(['message' => 'Invoice not found'], 404);
            }
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Invoice not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }


    /**
     * Get invoices for the current day's DailyBox and where customer is assigned to the box.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getInvoicesForToday(Request $request): JsonResponse
    {
        $user = Auth::user();
        $box = Box::getUserBox($user->id);

        if (!$box) {
            return response()->json(['message' => 'No box assigned to the user.'], 404);
        }

        $today = Carbon::now()->format('Y-m-d');
        $todayDailyBox = $box->dailyBoxes()->where('date', $today)->first();

        if (!$todayDailyBox) {
            return response()->json(['message' => 'No DailyBox found for today.'], 404);
        }

        $invoices = Invoice::where('daily_box_id', $todayDailyBox->id)->get();

        return response()->json(['invoices' => $invoices]);
    }

    public function getReceipt(Request $request)
    {
        $request->validate([
            'reference' => 'required|string'
        ]);

        $invoice = Invoice::findByDniOrInvoiceId($request->input('reference'));
        if ($invoice) {
            return view('invoices.receipt', ['invoice' => $invoice,'config' => $this->getConfig(),'qrCode' => QrCodeHelper::generateQrCode($invoice->increment_id)]);
        }

        abort(404);
    }

    private function getConfig(): array
    {
        return [
            'name' => GeneralProviderConfig::getCompanyName(),
            'address' => GeneralProviderConfig::getCompanyAddress(),
            'site' => GeneralProviderConfig::getCompanyUrl()
        ];
    }
}
