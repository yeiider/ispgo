<?php

namespace App\Helpers;

use App\Models\CoreConfigData;
use Illuminate\Support\Facades\Cache;

class ConfigHelper
{
    /**
     * Get the value of a configuration by path with a default scope_id of 0.
     *
     * @param string $path
     * @param int $scopeId
     * @return string|null
     */
    public static function getConfigValue(string $path, int $scopeId = 0): ?string
    {
        $cacheKey = "config_{$path}_{$scopeId}";

        // Check if the value is in cache
        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($path, $scopeId) {
            return CoreConfigData::getValueByPath($path, $scopeId);
        });
    }
}
