<?php

namespace App\Http\Middleware;

use Closure;
use App\Settings\CustomerConfigProvider;

class AllowCustomerRegistration
{
    public function handle($request, Closure $next)
    {
        if (!CustomerConfigProvider::getAllowCustomerRegistration()) {
            abort(404);
        }

        return $next($request);
    }
}
