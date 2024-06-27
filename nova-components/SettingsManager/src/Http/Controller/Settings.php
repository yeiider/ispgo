<?php

namespace Ispgo\SettingsManager\Http\Controller;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class Settings extends Controller
{
    private Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function settings()
    {
        $params = $this->request->section??'general';
        $key = "system.{$params}.general";
        $generalConfig = config('system');
        $sectionsConfig = config($key);
        $menusConfig = array_keys($generalConfig);
        $menu = [];
        foreach ($menusConfig as $menuConfig) {
            $menu[$menuConfig] = $generalConfig[$menuConfig]["setting"];
        }
        return compact('sectionsConfig', 'menu');
    }
}
