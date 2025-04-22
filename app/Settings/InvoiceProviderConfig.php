<?php

namespace App\Settings;

use App\Helpers\ConfigHelper;

class InvoiceProviderConfig
{
    const PATH_INVOICE = "invoice/general/";

    public static function sendEmailByPaying()
    {
        return ConfigHelper::getConfigValue(self::PATH_INVOICE . 'send_email_when_paying');
    }

    public static function enableServiceWhenPaying()
    {
        return ConfigHelper::getConfigValue(self::PATH_INVOICE . 'enable_service_when_paying');
    }

    public static function createPaymentPromise()
    {
        return ConfigHelper::getConfigValue(self::PATH_INVOICE . 'create_payment_promise');
    }

    public static function enableServiceByPaymentPromise()
    {
        return ConfigHelper::getConfigValue(self::PATH_INVOICE . 'enable_service_by_payment_promise');
    }

    public static function enablePartialPayment()
    {
        return ConfigHelper::getConfigValue(self::PATH_INVOICE . 'enable_partial_payment');
    }

    public static function emailTemplatePaying()
    {
        return ConfigHelper::getConfigValue(self::PATH_INVOICE . 'email_template_paying');
    }

    public static function sendEmailCreateInvoice()
    {
        return ConfigHelper::getConfigValue(self::PATH_INVOICE . 'send_email_create_invoice');
    }

    public static function bccInvoiceTo()
    {
        return ConfigHelper::getConfigValue(self::PATH_INVOICE . 'bcc_invoice_to');
    }

    public static function emailTemplateCreatedInvoice()
    {
        return ConfigHelper::getConfigValue(self::PATH_INVOICE . 'email_template_created_invoice');
    }

    public static function attachInvoice()
    {
        return ConfigHelper::getConfigValue(self::PATH_INVOICE . 'attach_invoice');
    }
}
