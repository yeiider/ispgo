<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invoice\Invoice;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InvoiceApi extends Controller
{
    public function searchInvoices(Request $request): JsonResponse
    {
        $request->validate([
            'q' => 'required|string'
        ]);

        try {
            $query = $request->input('q');
            $invoices = Invoice::searchInvoice($query);
            if ($invoices->isNotEmpty()) {
                $parsed = $invoices->map(fn (Invoice $invoice) => $this->parseData($invoice))->values();
                return response()->json(['invoices' => $parsed]);
            } else {
                return response()->json(['message' => __('Invoices not found')], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => __($e->getMessage())], 500);
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
            "products" => $invoice->products,
            "status" => $invoice->status,
            "issue_date" => $invoice->issue_date,
            "due_date" => $invoice->due_date,
            "customer" => $invoice->customer,
            "address" => $invoice->customer->addresses()->first()->address,
        ];

    }

    public function registerPayment(Request $request): JsonResponse
    {
        $request->validate([
            'reference' => 'required|string',
            'method' => 'required|string',
            'amount' => 'required|numeric',
            'additional_information' => 'nullable'
        ]);
        /**
         * @var $invoiceModel Invoice
         **/
        try {
            $reference = $request->input('reference');
            $invoiceModel = Invoice::findByDniOrInvoiceId($reference);
            if ($invoiceModel) {
                $invoiceModel->applyPayment(amount: $request->input("amount"),
                    paymentMethod: $request->input('method'),
                    additional: $request->input('additional_information'));

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
}
