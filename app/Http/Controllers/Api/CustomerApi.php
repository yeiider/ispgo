<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customers\Customer;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CustomerApi extends Controller
{
    public function search(Request $request): \Illuminate\Http\JsonResponse
    {
        // Validar los datos de entrada
        $request->validate([
            'input' => 'required|string'
        ]);

        $input = $request->input('input');

        try {
            $customers = Customer::searchCustomersWithInvoices($input);

            if ($customers->isNotEmpty()) {
                // Formatear los datos de los clientes y sus facturas
                $response = $customers->map(function ($customer) {
                    return [
                        "customer_id" => $customer->id,
                        "full_name" => $customer->full_name,
                        "email_address" => $customer->email_address,
                        "invoices" => $customer->invoices->map(function ($invoice) {
                            return [
                                "subtotal" => $invoice->subtotal,
                                "increment_id" => $invoice->increment_id,
                                "tax" => $invoice->tax,
                                "total" => $invoice->total,
                                "amount" => $invoice->amount,
                                "discount" => $invoice->discount,
                                "status" => $invoice->status,
                                "issue_date" => $invoice->issue_date,
                                "due_date" => $invoice->due_date,
                            ];
                        })
                    ];
                });

                return response()->json($response);
            } else {
                return response()->json(['message' => 'No customers found'], 404);
            }
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'No customers found'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred while searching for customers'], 500);
        }
    }
}
