<?php

namespace Ispgo\Ckeditor\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Laravel\Nova\Nova;
use Ispgo\Ckeditor\Ckeditor;
use Laravel\Nova\Tool;

class Authorize
{
    /**
     * Handle the incoming request.
     *
     * @param  Request  $request
     * @param  \Closure(Request):mixed  $next
     * @return Response
     */
    public function handle($request, $next)
    {
        return  $next($request);
    }

    /**
     * Determine whether this tool belongs to the package.
     *
     * @param  Tool  $tool
     * @return bool
     */
    public function matchesTool($tool)
    {
        return $tool instanceof Ckeditor;
    }
}
