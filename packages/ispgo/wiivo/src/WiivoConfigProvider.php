<?php

namespace Ispgo\Wiivo;

use App\Helpers\ConfigHelper;

class WiivoConfigProvider
{
    public static function getEnabled(): ?bool
    {
        return self::getValue('enabled');
    }

    public static function getUrlApi(): ?string
    {
        return self::getValue('url_api');
    }

    public static function getApiKey(): ?string
    {
        return self::getValue('api_key');
    }

    public static function getTelephonePrefix(): ?string
    {
        return self::getValue('telephone_prefix');
    }

    public static function getSessionLife(): ?string
    {
        return self::getValue('session_life');
    }

    public static function getWelcomeMessage(): ?string
    {
        return self::getValue('welcome_message');
    }

    public static function getNotifyPayment(): ?bool
    {
        return self::getValue('notify_payment');
    }

    public static function getNotifyPaymentTemplate(): ?string
    {
        return self::getValue('notify_payment_template');
    }

    public static function getNotifyInvoice(): ?bool
    {
        return self::getValue('notify_invoice');
    }

    public static function getNotifyInvoiceTemplate(): ?string
    {
        return self::getValue('notify_invoice_template');
    }

    public static function getCheckInvoice(): ?bool
    {
        return self::getValue('check_invoice');
    }

    public static function getPayByWhatsapp(): ?bool
    {
        return self::getValue('pay_by_whatsapp');
    }

    public static function getCreateTicket(): ?bool
    {
        return self::getValue('create_ticket');
    }

    public static function getEnv(): ?string
    {
        return self::getValue('env');
    }

    public static function getTelephoneTest(): ?array
    {
        return explode(',', self::getValue('telephone_test'));
    }

    private static function getValue($field): ?string
    {
        return ConfigHelper::getConfigValue("notifications/wiivo/$field");
    }
}
