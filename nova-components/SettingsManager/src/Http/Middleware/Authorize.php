<?php

namespace Ispgo\SettingsManager\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Laravel\Nova\Nova;
use Ispgo\SettingsManager\SettingsManager;
use Closure;

class Authorize
{
    /**
     * Handle the incoming request.
     *
     * @param  Request  $request
     * @param  \Closure(Request):mixed  $next
     * @return Response
     */
    public function handle($request, Closure $next)
    {
        $tool = collect(Nova::registeredTools())->first([$this, 'matchesTool']);

        if ($tool && $this->userHasSettingPermission($request)) {
            return $next($request);
        }

        return abort(403);
    }

    /**
     * Determine whether this tool belongs to the package.
     *
     * @param  \Laravel\Nova\Tool  $tool
     * @return bool
     */
    public function matchesTool($tool)
    {
        return $tool instanceof SettingsManager;
    }

    /**
     * Determine whether the user has the 'setting' permission.
     *
     * @param  Request  $request
     * @return bool
     */
    protected function userHasSettingPermission($request)
    {
        return $request->user() && $request->user()->can('Setting');
    }
}
