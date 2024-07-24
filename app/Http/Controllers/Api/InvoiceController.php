<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invoice\Invoice;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class InvoiceController extends Controller
{
    public function search(Request $request): \Illuminate\Http\JsonResponse
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
}
