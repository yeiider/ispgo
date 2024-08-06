<?php

namespace App\Settings;

use App\Helpers\ConfigHelper;

class GeneralProviderConfig
{
    const GENERAL_PATH = "general/general/";
    const BILLING_PATH = "general/billing_cycle/";

    public static function getCompanyName(): ?string
    {
        return ConfigHelper::getConfigValue(self::GENERAL_PATH . 'company_name');
    }

    public static function getCompanyLogo(): ?string
    {
        return ConfigHelper::getConfigValue(self::GENERAL_PATH . 'company_logo');
    }

    public static function getCompanyAddress(): ?string
    {
        return ConfigHelper::getConfigValue(self::GENERAL_PATH . 'company_address');
    }

    public static function getCompanyPhone(): ?string
    {
        return ConfigHelper::getConfigValue(self::GENERAL_PATH . 'company_telephone');
    }

    public static function getCompanyDescription(): ?string
    {
        return ConfigHelper::getConfigValue(self::GENERAL_PATH . 'company_description');
    }

    public static function getCompanyUrl(): ?string
    {
        return ConfigHelper::getConfigValue(self::GENERAL_PATH . 'company_url');
    }

    public static function getCompanyEmail(): ?string
    {
        return ConfigHelper::getConfigValue(self::GENERAL_PATH . 'company_email');
    }

    public static function getBillingDate(): ?int
    {
        $billingDate = ConfigHelper::getConfigValue(self::BILLING_PATH . 'billing_date');
        return is_numeric($billingDate) && $billingDate >= 1 && $billingDate <= 31 ? (int)$billingDate : 1;
    }

    public static function getCutOffDate(): ?int
    {
        $cutOffDate = ConfigHelper::getConfigValue(self::BILLING_PATH . 'cut_off_date');
        return is_numeric($cutOffDate) && $cutOffDate >= 1 && $cutOffDate <= 31 ? (int)$cutOffDate : 1;
    }

    public static function getPaymentDueDate(): ?int
    {
        $dueDate = ConfigHelper::getConfigValue(self::BILLING_PATH . 'payment_due_date');
        return is_numeric($dueDate) && $dueDate >= 1 && $dueDate <= 31 ? (int)$dueDate : 1;

    }

    public static function getAutomaticCutOff(): ?bool
    {
        return (bool)ConfigHelper::getConfigValue(self::BILLING_PATH . 'automatic_cut_off');
    }

    public static function getAutomaticInvoiceGeneration(): ?bool
    {
        return (bool)ConfigHelper::getConfigValue(self::BILLING_PATH . 'automatic_invoice_generation');
    }

    public static function getSendPaymentReminders(): ?bool
    {
        return ConfigHelper::getConfigValue(self::BILLING_PATH . 'send_payment_reminders');
    }

    public static function getLateFeePercentage(): ?string
    {
        return ConfigHelper::getConfigValue(self::BILLING_PATH . 'late_fee_percentage');
    }

    public static function getGracePeriodDays(): ?string
    {
        return ConfigHelper::getConfigValue(self::BILLING_PATH . 'grace_period_days');
    }

    public static function getDefaultUser(): ?int
    {
        $defaultUser = ConfigHelper::getConfigValue(self::BILLING_PATH . 'default_user');
        return is_numeric($defaultUser) ? (int)$defaultUser : null;
    }
}
