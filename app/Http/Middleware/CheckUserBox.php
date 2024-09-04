<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Box;

class CheckUserBox
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        $box = Box::getUserBox($user->id);

        if (!$box) {
            abort(404, 'User does not have an assigned box.');
        }

        return $next($request);
    }
}
