<?php

namespace App\Http\Middleware;

use Closure;
use App\Settings\CustomerConfigProvider;

class AllowLogin
{
    public function handle($request, Closure $next)
    {
        if (!CustomerConfigProvider::getAllowLogin()) {
            abort(404);
        }

        return $next($request);
    }
}
