<?php

namespace App\Http\Controllers\CustomerAccount;

use App\Http\Controllers\Controller;
use App\Models\Customers\Customer;
use App\Settings\Config\Sources\DocumentType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Http\{Request, RedirectResponse};
use Inertia\Inertia;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{

    public function edit(): \Inertia\Response
    {
        $documentTypes = DocumentType::getConfig();
        $routeChangePassword = route('customer.changePassword');
        return Inertia::render('Customer/Edit',
            compact('documentTypes', 'routeChangePassword')
        );
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email_address' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('customers')->ignore($id),
            ],
            'date_of_birth' => [
                'nullable',
                'date',
                function ($attribute, $value, $fail) {
                    if ($value) {
                        $birthDate = \Carbon\Carbon::parse($value);
                        $today = \Carbon\Carbon::now();
                        $age = $birthDate->diffInYears($today);
                        
                        // Verificar que tenga al menos 18 años completos
                        if ($age < 18 || ($age == 18 && $birthDate->copy()->addYears(18)->isFuture())) {
                            $fail('El cliente debe ser mayor de edad (18 años o más).');
                        }
                    }
                }
            ],
        ]);

        $customer = Customer::findOrFail($id);

        $customer->update($request->only([
            'first_name',
            'last_name',
            'email_address',
            'date_of_birth',
            'phone_number',
            'document_type',
            'identity_document'
        ]));


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


    /**
     * Changes the password for the authenticated customer.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException|\ErrorException If validation fails.
     */
    public function changePassword(Request $request): RedirectResponse
    {
        $customer = $this->getCustomer();

        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, $customer->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Current password is incorrect']);
        }

        $customer->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('index');
    }

}
