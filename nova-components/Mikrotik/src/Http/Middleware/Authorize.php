<?php

namespace Ispgo\Mikrotik\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Laravel\Nova\Nova;
use Ispgo\Mikrotik\Mikrotik;
use Laravel\Nova\Tool;

class Authorize
{
    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @param \Closure(Request):mixed $next
     * @return Response
     */
    public function handle($request, $next)
    {
        $tool = collect(Nova::registeredTools())->first([$this, 'matchesTool']);
        if ($tool && $this->userHasSettingPermission($request)) {
            return $next($request);
        }
        abort(403);
    }

    /**
     * Determine whether this tool belongs to the package.
     *
     * @param Tool $tool
     * @return bool
     */
    public function matchesTool($tool)
    {
        return $tool instanceof Mikrotik;
    }

    /**
     * Determine whether the user has the 'setting' permission.
     *
     * @param Request $request
     * @return bool
     */
    protected function userHasSettingPermission($request)
    {
        //return $request->user() && $request->user()->can('Setting');
        return true;
    }
}
