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
        return CoreConfigData::getValueByPath($path, $scopeId);
    }

    /**
     * Get payment methods from configuration
     *
     * This method fetches the payment methods that have been defined in the "settings.payment"
     * configuration. The methods are returned in an array format.
     *
     * @return array An array of payment methods
     */
    public static function getPaymentsMethods(): array
    {
        $paymentsConfig = config('settings.payment', []);

        // Filter out any keys that are not payment methods (e.g., "setting" key)
        return array_filter(array_keys($paymentsConfig), function ($key) {
            return $key !== 'setting';
        });
    }

}
