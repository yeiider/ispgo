<?php

namespace App\Http\Controllers\CustomerAccount;

use App\Http\Controllers\Controller;
use App\Models\Customers\Customer;
use App\Settings\Config\Sources\DocumentType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Http\{Request, RedirectResponse};
use Inertia\Inertia;

class CustomerController extends Controller
{

    public function edit(): \Inertia\Response
    {
        $documentTypes = DocumentType::getConfig();
        $routeUpdateCustomer = route('customer.update');
        return Inertia::render('CustomerAccount/Edit',
            compact('documentTypes', 'routeUpdateCustomer')
        );
    }

    public function update(Request $request)
    {

        $customer = $this->getCustomer();

        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email_address' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('customers')->ignore($customer->id),
            ],
        ]);

        $customer->update($request->only(
            'first_name',
            'last_name',
            'email_address',
            'date_of_birth',
            'phone_number',
            'document_type'
        ));
        return redirect()->route('index');
    }


    /**
     * @throws \ErrorException
     */
    private function getCustomer(): \Illuminate\Contracts\Auth\Authenticatable
    {
        if (Auth::guard('customer')->check()) {
            //return Customer::find(Auth::guard('customer')->id());
            return Auth::guard('customer')->user();
        } else {
            throw new \ErrorException('You are not allowed to access this page');
        }
    }

}
