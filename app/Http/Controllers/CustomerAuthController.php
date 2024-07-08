<?php

namespace App\Http\Controllers;

use App\Models\Customers\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CustomerAuthController extends Controller
{
    public function showRegistrationForm(): \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('auth.customer-register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email_address' => 'required|string|email|max:255|unique:customers',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $customer = Customer::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email_address' => $request->email_address,
            'password' => Hash::make($request->password),
        ]);

        Auth::guard('customer')->login($customer);

        return redirect()->route('customer.dashboard');
    }

    public function showLoginForm()
    {
        return view('auth.customer-login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email_address' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (Auth::guard('customer')->attempt(['email_address' => $request->email_address, 'password' => $request->password])) {
            return redirect()->route('customer.dashboard');
        }

        return back()->withErrors([
            'email_address' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout()
    {
        Auth::guard('customer')->logout();
        return redirect()->route('customer.login');
    }

}
