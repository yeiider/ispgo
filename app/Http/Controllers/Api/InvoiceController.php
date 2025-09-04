<?php

namespace App\Http\Controllers\Api;

use App\Helpers\QrCodeHelper;
use App\Helpers\Utils;
use App\Http\Controllers\Controller;
use App\Models\Box;
use App\Models\DailyBox;
use App\Models\Invoice\Invoice;
use App\Settings\GeneralProviderConfig;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
            if ($invoiceModel) {
                $invoice = $this->parseData($invoiceModel);
                return response()->json(compact('invoice'));
            } else {
                return response()->json(['message' => __('Invoice not found')], 404);
            }
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => __('Invoice not found')], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => __('An error occurred while searching for the invoice')], 500);
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
                return response()->json(['message' => __('Payment registered successfully'), 'data' => $invoiceModel, 'status' => 200]);
            } else {
                return response()->json(['message' => __('Invoice not found')], 404);
            }
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => __('Invoice not found')], 404);
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
            return view('invoices.receipt', ['invoice' => $invoice, 'config' => $this->getConfig(), 'qrCode' => QrCodeHelper::generateQrCode($invoice->increment_id)]);
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

    public function previewInvoice($id)
    {
        $invoice = $this->getInvoice($id);

        if (!$invoice) {
            abort(404, __('Invoice not found'));
        }

        $options = ['locale' => 'es', 'currency' => 'COP'];

        // Create a data array with all necessary variables
        $data = [
            'invoice' => $invoice,
            'companyName' => GeneralProviderConfig::getCompanyName() ?? env('APP_NAME'),
            'companyEmail' => GeneralProviderConfig::getCompanyEmail() ?? env('MAIL_FROM_ADDRESS'),
            'companyPhone' => GeneralProviderConfig::getCompanyPhone() ?? "",
            'companyAddress' => GeneralProviderConfig::getCompanyAddress() ?? "",
            'items' => $invoice->items,
            'tax_rate' => $invoice->tax_rate ?? 0,
            'tax_amount' => $invoice->tax ?? 0,
        ];

        // Format numeric values
        $data['invoice']->total = Utils::priceFormat($invoice->total, $options);
        $data['invoice']->subtotal = Utils::priceFormat($invoice->subtotal, $options);
        $data['invoice']->tax = Utils::priceFormat($invoice->tax, $options);

        // Handle company logo
        $imgPath = GeneralProviderConfig::getCompanyLogo() ?? public_path('img/invoice.svg');
        $imgPath = Str::after($imgPath, 'storage/');
        if (Storage::disk('public')->exists($imgPath)) {
            $imgContent = Storage::disk('public')->get($imgPath);
            $data['img'] = 'data:image/png;base64,' . base64_encode($imgContent);
        } else {
            $data['img'] = '';
        }

        //$pdfContent = Pdf::loadView('invoices.preview', compact('data'));
        //Storage::put($filePath, $pdfContent->output());

        return view('invoices.preview', compact('data'));


    }

    public function previewInvoiceEmail($id): \Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        $invoice = $this->getInvoice($id);

        if (!$invoice) {
            abort(404, __('Invoice not found'));
        }

        $url_pay = route('checkout.index') . '?invoice=' . $invoice->increment_id;
        $qr_image = QrCodeHelper::generateQrCode($invoice->increment_id);

        $url = Utils::generateFormattedUrl(env('APP_URL'));
        $issue_month = Utils::getMonthFormDate($invoice->issue_date);
        $total_amount = Utils::priceFormat($invoice->total, ['locale' => 'es', 'currency' => 'COP']);
        $due_date = Utils::formatToDayAndMonth($invoice->due_date);
        $url_preview = route('preview.invoice', $invoice->increment_id);
        $img_header = asset('img/invoice/email-header.jpeg');


        $companyName = GeneralProviderConfig::getCompanyName() ?? env('APP_NAME');

        return view('emails.invoice',
            compact(
                'invoice',
                'img_header',
                'companyName'
            )
        );
    }

    public function previewReceipt($id)
    {
        $invoice = $this->getInvoice($id);
        if (!$invoice) {
            abort(404, __('Invoice not found'));
        }
        if ($invoice->status !== 'paid') {
            abort(403, __('Only paid invoices have a printable receipt.'));
        }
        $qrCode = QrCodeHelper::generateQrCode($invoice->increment_id);
        $config = $this->getConfig();
        return view('invoices.receipt', compact('invoice', 'config', 'qrCode'));
    }

    private function getInvoice(int|string $id)
    {
        return Invoice::where('increment_id', $id)
            ->with('customer')
            ->first();
    }


}
