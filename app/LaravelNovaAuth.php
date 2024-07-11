<?php

namespace App;

use PHPageBuilder\Contracts\AuthContract;
use Illuminate\Support\Facades\Auth;

class LaravelNovaAuth implements AuthContract
{
    /**
     * Process the current GET or POST request and redirect or render the requested page.
     *
     * @param $action
     */
    public function handleRequest($action)
    {
        if (phpb_in_module('auth')) {
            if ($action === 'logout') {
                Auth::logout();
                session()->invalidate();
                session()->regenerateToken();
                phpb_redirect(config('nova.path').'/login'); // Cambia esto a la ruta de login de Laravel Nova
            }
        }
    }

    /**
     * Return whether the current request has an authenticated session.
     *
     * @return bool
     */
    public function isAuthenticated()
    {
        return Auth::check();
    }

    /**
     * If the user is not authenticated, show the login form.
     */
    public function requireAuth()
    {
        if (! $this->isAuthenticated()) {
            redirect()->guest('/nova/login')->send(); // Cambia esto a la ruta de login de Laravel Nova
        }
    }

    /**
     * Render the login form.
     */
    public function renderLoginForm()
    {
        redirect()->guest('/nova/login')->send(); // Cambia esto a la ruta de login de Laravel Nova
    }
}
