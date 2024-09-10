<?php

namespace App\Settings;

use App\Helpers\ConfigHelper;

class SupportProviderConfig
{
    const PATH_SUPPORT = "invoice/general/";

    public static function allowCustomerCreateTickets(): bool
    {
        return (bool)ConfigHelper::getConfigValue(self::PATH_SUPPORT . 'allow_create_ticket');
    }

    public static function notifyCustomer(): bool
    {
        return (bool)ConfigHelper::getConfigValue(self::PATH_SUPPORT . 'notify_client');
    }

    public static function defaultTicketProperty(): ?string
    {
        return ConfigHelper::getConfigValue(self::PATH_SUPPORT . 'ticket_priority');
    }

    public static function defaultTicketStatus(): ?string
    {
        return ConfigHelper::getConfigValue(self::PATH_SUPPORT . 'ticket_status');
    }

    public static function notifyTechnician(): bool
    {
        return (bool)ConfigHelper::getConfigValue(self::PATH_SUPPORT . 'notify_technician');
    }

    public static function allowCustomerToCloseTickets(): bool
    {
        return (bool)ConfigHelper::getConfigValue(self::PATH_SUPPORT . 'allow_client_close_ticket');
    }

    public static function notifyByEmail(): bool
    {
        return (bool)ConfigHelper::getConfigValue(self::PATH_SUPPORT . 'notify_by_email');
    }

    public static function emailTemplateForTechnicianNotification(): ?string
    {
        return ConfigHelper::getConfigValue(self::PATH_SUPPORT . 'email_template_technician');
    }

    public static function emailTemplateForChangeStatus(): ?string
    {
        return ConfigHelper::getConfigValue(self::PATH_SUPPORT . 'email_template_change_status');
    }
}
