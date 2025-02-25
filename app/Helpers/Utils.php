<?php

/**
 * Class Utils
 * This class provides helper utility methods for formatting prices, URLs, and dates.
 */

namespace App\Helpers;

class Utils
{

    /**
     * Formats a price string into a localized currency format.
     *
     * This method takes a price as a string and formats it as currency based on the specified
     * locale and currency. It uses the `NumberFormatter` class to handle the formatting. A default
     * locale of "en" (English) and currency of "USD" (US Dollars) is provided if no options are given.
     *
     * @param string $price The numeric price value as a string (e.g., "1234.56").
     * @param array $options Optional settings for formatting:
     *                       - `locale` (string): The locale used for formatting (e.g., "en", "es", "fr").
     *                       - `currency` (string): The ISO 4217 currency code (e.g., "USD", "EUR").
     *                       Defaults to `['locale' => 'en', 'currency' => 'USD']`.
     * @return string The formatted price string (e.g., "$1,234.56" for "en" locale and USD currency).
     *
     * Example usage:
     * ```php
     * $formattedPrice = PriceHelper::priceFormat('1234.56', ['locale' => 'es', 'currency' => 'EUR']);
     * echo $formattedPrice; // Outputs: "1.234,56 €" (for Spanish locale and Euros)
     *
     * $formattedPrice = PriceHelper::priceFormat('1234.56');
     * echo $formattedPrice; // Outputs: "$1,234.56" (default locale and currency)
     * ```
     *
     * @throws \InvalidArgumentException If an invalid locale or currency is provided.
     */
    public static function priceFormat(string $price, array $options = ['locale' => 'en', 'currency' => 'USD']): string
    {
        $formatter = new \NumberFormatter($options['locale'], \NumberFormatter::CURRENCY);
        return $formatter->formatCurrency((float)$price, $options['currency']);
    }


    /**
     * Generates a formatted URL object from a given URL string.
     *
     * This method takes a URL as input, reformats it by replacing the protocol
     * (`http://` or `https://`) with `www.`, and returns an object containing both the
     * original URL and the formatted version. The returned object is an instance of `stdClass`.
     *
     * @param string $url The input URL (e.g., "https://example.com").
     * @return \stdClass An object with two properties:
     *                   - `value`: The original URL.
     *                   - `label`: The formatted URL, starting with "www.".
     *
     * Example usage:
     * ```php
     * $formattedUrl = UrlHelper::generateFormattedUrl('https://example.com');
     * echo $formattedUrl->value; // Outputs: "https://example.com"
     * echo $formattedUrl->label; // Outputs: "www.example.com"
     * ```
     */
    public static function generateFormattedUrl(string $url): \stdClass
    {
        $formattedLabel = preg_replace('/^(https?:\/\/)/', 'www.', $url);

        $formattedUrl = new \stdClass();
        $formattedUrl->value = $url;
        $formattedUrl->label = $formattedLabel;

        return $formattedUrl;
    }

    /**
     * Extracts the month name from a given date in Spanish.
     *
     * This method takes a date as input, parses it using Carbon, and returns the full
     * month name translated into Spanish. The method relies on the system's locale configuration
     * to provide the appropriate translation.
     *
     * @param string $date The input date in a valid PHP date format (e.g., 'Y-m-d', 'd/m/Y').
     * @return string The full name of the month (e.g., "Febrero").
     */
    public static function getMonthFormDate($date): string
    {
        return \Carbon\Carbon::parse($date)->translatedFormat('F');
    }


    /**
     * Formats a date to the "05 de February" format.
     *
     * This method takes a date string as input and converts it to a human-readable
     * "day de month" format in Spanish (e.g., "05 de February"). It uses Carbon
     * to handle date parsing and formatting.
     *
     * @param string $date The input date in a valid PHP date format (e.g., 'Y-m-d', 'd/m/Y').
     * @return string The formatted date in the "day de month" format.
     *
     **/
    public static function formatToDayAndMonth(string $date): string
    {
        return \Carbon\Carbon::parse($date)->translatedFormat('d \d\e F');
    }

    public static function imageToBase64(string $path): string
    {
        if (file_exists($path)) {
            $img = base64_encode(file_get_contents($path));
            return 'data:image/svg+xml;base64,' . $img;
        }
        return '';
    }

}
