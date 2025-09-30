<?php

namespace App\Nova\Settings;

use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;

class OnePayProviderConfig
{
    /**
     * Return the fields that should appear in Nova Settings UI for OnePay.
     */
    public static function fields(): array
    {
        return [
            Boolean::make('onepay_enabled', 'onepay_enabled')->help('Enable or disable OnePay integration'),
            Text::make('onepay_base_url', 'onepay_base_url')->help('Base API URL, e.g., https://api.onepay.la/v1'),
            Text::make('onepay_api_token', 'onepay_api_token')->withMeta(['type' => 'password'])->help('Secret API token'),
            Number::make('onepay_auto_create_day', 'onepay_auto_create_day')->min(1)->max(31)->nullable()->help('Day of month to auto-create charges'),
            Number::make('onepay_auto_remind_day', 'onepay_auto_remind_day')->min(1)->max(31)->nullable()->help('Day of month to auto-remind'),
        ];
    }
}
