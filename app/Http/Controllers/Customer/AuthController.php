<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Settings\Config\Sources\DocumentType;
use Illuminate\Http\{Request, RedirectResponse};
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AuthController extends Controller
{
    private const LOGIN_FORM_PATH = 'Customer/Login';
    private const REGISTer_FORM_PATH = 'Customer/Register';

    /**
     * Display the login form.
     *
     * @return \Inertia\Response
     */
    public function showLoginForm(): \Inertia\Response
    {
        return Inertia::render(self::LOGIN_FORM_PATH, []);
    }


    /**
     * Handle a login request for the application.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->only('email_address', 'password');
        if (Auth::guard('customer')->attempt($credentials)) {
            return redirect()->intended(route('customer.dashboard'));
        }

        return back()->withErrors([
            'error' => 'The provided credentials do not match our records.',
        ]);
    }


    /**
     * Logout the customer.
     *
     * @return RedirectResponse
     */
    public function logout(): RedirectResponse
    {
        Auth::guard('customer')->logout();
        return redirect('/');
    }

    public function showRegisterForm(): \Inertia\Response
    {
        $documentTypes = DocumentType::getConfig();

        return Inertia::render(self::REGISTer_FORM_PATH, [
            'documentTypes' => $documentTypes
        ]);
    }

    public function register(Request $request)
    {

    }
}
