<?php

namespace App\Http\Controllers\CustomerAccount;

use App\Http\Controllers\Controller;
use App\Models\Customers\Customer;
use App\Models\PasswordReset;
use App\Settings\Config\Sources\DocumentType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Http\{Request, RedirectResponse};
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AuthController extends Controller
{
    private const LOGIN_FORM_PATH = 'Customer/Authentication/Login';
    private const REGISTer_FORM_PATH = 'Customer/Authentication/Register';

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
            return redirect()->intended(route('index'));
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

    /**
     * Display the registration form.
     *
     * @return \Inertia\Response
     */
    public function showRegisterForm(): \Inertia\Response
    {
        $documentTypes = DocumentType::getConfig();

        return Inertia::render(self::REGISTer_FORM_PATH, [
            'documentTypes' => $documentTypes
        ]);
    }

    /**
     * Handle customer registration.
     *
     * Validates the incoming request data, creates a new customer, logs them in,
     * sends an email verification notification, and redirects to the index route.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function register(Request $request): RedirectResponse
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
            'document_type' => $request->document_type,
            'identity_document' => $request->identity_document,
            'email_address' => $request->email_address,
            'password' => Hash::make($request->password),
        ]);

        Auth::guard('customer')->login($customer);
        $customer->sendEmailVerificationNotification();

        return redirect()->route('index');
    }


    /**
     * Display the password reset form.
     *
     * @return \Inertia\Response
     */
    public function showPasswordResetForm(): \Inertia\Response
    {
        return Inertia::render('Customer/Authentication/ResetPassword', []);
    }

    /**
     * Send a password reset link to the given customer.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendPasswordResetEmail(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'email_address' => 'required|string|email|max:255|exists:customers,email_address',
        ], [
            'email_address.exists' => __('This email address does not exist in our records.'),
        ]);

        $customer = Customer::where('email_address', $request->email_address)->first();

        if ($customer) {
            // Generate a password reset token and send email
            $token = Str::random(60);
            DB::table('password_resets')->insert([
                'email' => $request->email_address,
                'token' => $token,
                'created_at' => now(),
            ]);

            // Send email to the customer with the reset link
            Mail::send('emails.password_reset', ['token' => $token], function ($message) use ($customer) {
                $message->to($customer->email_address);
                $message->subject('Password Reset Request');
            });
        }

        return back()->with('status', __('We have emailed your password reset link!'));
    }

    /**
     * Display the create password form.
     *
     * @param Request $request
     * @param string $token
     * @return \Inertia\Response|RedirectResponse
     */
    public function showCreatePassword(Request $request, $token): \Inertia\Response|RedirectResponse
    {
        if (!$token) {
            return redirect()
                ->withErrors(['token' => 'Invalid token'])
                ->route('customer.password.reset');
        }

        $passwordReset = PasswordReset::where('token', $token)->firstOrFail();
        $email = $passwordReset->email;
        $customer = Customer::where('email_address', $email)->first();
        if (!$customer) {
            return redirect()->withErrors([
                'error' => 'This password reset token is invalid.',
            ])->route('customer.password.reset');
        }

        $routeCreatePassword = route('customer.password.create');
        return Inertia::render('Customer/Authentication/CreatePassword', compact('customer', 'routeCreatePassword'));
    }

    /**
     * Create a new password for the customer.
     *
     * This method validates the request data to ensure the email address and password are provided,
     * then it hashes the password and updates the customer's record in the database.
     * Finally, it redirects to the customer login route with a status message.
     *
     * @param Request $request The HTTP request instance containing the form data.
     * @return RedirectResponse The HTTP redirect response to the customer login route.
     */
    public function createPassword(Request $request): RedirectResponse
    {
        $request->validate([
            'email_address' => 'required|string|email|max:255|exists:customers,email_address',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'email_address.exists' => __('This email address does not exist in our records.'),
            'password.confirmed' => __('The password confirmation does not match.'),
        ]);

        $customer = Customer::where('email_address', $request->email_address)->first();
        $customer->password = Hash::make($request->password);
        $customer->save();

        return redirect()
            ->route('customer.login')
            ->with('status', 'Your password has been created!');
    }
}
