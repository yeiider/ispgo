<?php

namespace App\Helpers;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class Settings
{
    public static function get($key, $default = null)
    {
        return Cache::rememberForever("settings.{$key}", function () use ($key, $default) {
            $setting = Setting::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }

    public static function set($key, $value)
    {
        Cache::forget("settings.{$key}");
        return Setting::updateOrCreate(['key' => $key], ['value' => $value]);
    }
}
