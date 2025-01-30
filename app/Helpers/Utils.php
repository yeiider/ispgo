<?php

namespace App\Helpers;

class Utils
{

    public static function priceFormat(string $price, array $options = ['locale' => 'en', 'currency' => 'USD']): string
    {
        $formatter = new \NumberFormatter($options['locale'], \NumberFormatter::CURRENCY);
        return $formatter->formatCurrency((float)$price, $options['currency']);
    }

}
