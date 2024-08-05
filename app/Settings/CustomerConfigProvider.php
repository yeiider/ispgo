<?php

namespace App\Settings;

use App\Helpers\ConfigHelper;

class CustomerConfigProvider
{
    const GENERAL_PATH = "customer/general/";
    const SECURITY_PATH = "customer/security/";

    // General Customer settings
    public static function getAllowLogin(): ?string
    {
        return ConfigHelper::getConfigValue(self::GENERAL_PATH . 'allow_login');
    }

    public static function getAllowPaymentAsAGuest(): ?string
    {
        return ConfigHelper::getConfigValue(self::GENERAL_PATH . 'allow_payment_as_a_guest');
    }

    public static function getSendWelcomeEmail(): ?string
    {
        return ConfigHelper::getConfigValue(self::GENERAL_PATH . 'send_welcome_email');
    }

    public static function getSendWelcomeEmailTemplate(): ?string
    {
        return ConfigHelper::getConfigValue(self::GENERAL_PATH . 'send_welcome_email_template');
    }

    public static function getEmailConfirmationAccountConfirmation(): ?string
    {
        return ConfigHelper::getConfigValue(self::GENERAL_PATH . 'email_confirmation_account_confirmation');
    }

    public static function getEmailConfirmationAccountTemplate(): ?string
    {
        return ConfigHelper::getConfigValue(self::GENERAL_PATH . 'email_confirmation_account_template');
    }

    public static function getAllowRequestingANewService(): ?string
    {
        return ConfigHelper::getConfigValue(self::GENERAL_PATH . 'allow_requesting_a_new_service');
    }

    public static function getSavePaymentMethods(): ?string
    {
        return ConfigHelper::getConfigValue(self::GENERAL_PATH . 'save_payment_methods');
    }

    public static function getAllowCustomerRegistration(): ?string
    {
        return ConfigHelper::getConfigValue(self::GENERAL_PATH . 'allow_customer_registration');
    }

    // Security settings
    public static function getApiKey(): ?string
    {
        return ConfigHelper::getConfigValue(self::SECURITY_PATH . 'api_key');
    }

    public static function getShowInSignIn(): ?string
    {
        return ConfigHelper::getConfigValue(self::SECURITY_PATH . 'show_in_sign_in');
    }

    public static function getShowInSignUp(): ?string
    {
        return ConfigHelper::getConfigValue(self::SECURITY_PATH . 'show_in_sign_up');
    }
}
