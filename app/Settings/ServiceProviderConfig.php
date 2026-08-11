<?php

namespace App\Settings;

use App\Helpers\ConfigHelper;

class ServiceProviderConfig
{
    const PATH_SERVICE_GENERAL = "service/general/";
    const PATH_SERVICE_CONTRACT = "service/contract/";

    public static function createInstallationOrder(): bool
    {
        return (bool)ConfigHelper::getConfigValue(self::PATH_SERVICE_GENERAL . 'create_installation_order');
    }

    public static function notifyUserOnServiceCreation(): bool
    {
        return (bool)ConfigHelper::getConfigValue(self::PATH_SERVICE_GENERAL . 'notify_user_service_creation');
    }

    public static function showServicesInCustomerSection(): bool
    {
        return (bool)ConfigHelper::getConfigValue(self::PATH_SERVICE_GENERAL . 'show_services_in_customer_section');
    }

    public static function enableContracts(): bool
    {
        return (bool)ConfigHelper::getConfigValue(self::PATH_SERVICE_CONTRACT . 'enabled');
    }

    public static function contractTemplate(): ?int
    {
        return (int)ConfigHelper::getConfigValue(self::PATH_SERVICE_CONTRACT . 'contract_template');
    }

    public static function representativeSignature(): ?string
    {
        return ConfigHelper::getConfigValue(self::PATH_SERVICE_CONTRACT . 'representative_signature');
    }

    public static function representativeName(): ?string
    {
        return ConfigHelper::getConfigValue(self::PATH_SERVICE_CONTRACT . 'representative_name');
    }

    public static function representativeDocument(): ?string
    {
        return ConfigHelper::getConfigValue(self::PATH_SERVICE_CONTRACT . 'representative_document');
    }
    public static function representativeRole(): ?string
    {
        return ConfigHelper::getConfigValue(self::PATH_SERVICE_CONTRACT . 'representative_role');
    }

    public static function emailTemplateSend(): ?int
    {
        $val = ConfigHelper::getConfigValue(self::PATH_SERVICE_CONTRACT . 'email_template_send');
        return $val ? (int)$val : null;
    }

    public static function emailTemplateSigned(): ?int
    {
        $val = ConfigHelper::getConfigValue(self::PATH_SERVICE_CONTRACT . 'email_template_signed');
        return $val ? (int)$val : null;
    }

    public static function emailTemplateApproved(): ?int
    {
        $val = ConfigHelper::getConfigValue(self::PATH_SERVICE_CONTRACT . 'email_template_approved');
        return $val ? (int)$val : null;
    }

    public static function emailTemplateRejected(): ?int
    {
        $val = ConfigHelper::getConfigValue(self::PATH_SERVICE_CONTRACT . 'email_template_rejected');
        return $val ? (int)$val : null;
    }
}
