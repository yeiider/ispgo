<?php

return [
    "general" => [
        "setting" => [
            "label" => "General Setting",
            "class" => "form-control",
        ],
        "general" => [
            "setting" => [
                "label" => "Web Setting",
                "code" => "general"
            ],
            "company_status" => [
                "field" => "select-field",
                "label" => "Company Enabled?",
                "options" => \Ispgo\SettingsManager\Source\Config\Yesno::class
            ],
            "company_type" => [
                "field" => "select-field",
                "label" => "Company Type",
                "placeholder" => "Company Type",
                "options" => \Ispgo\SettingsManager\Source\Config\CompanyType::class
            ],
            "company_name" => [
                "field" => "text-field",
                "label" => "Company Name",
                "placeholder" => "Company Name",
            ],
            "company_description" => [
                "field" => "textarea-field",
                "label" => "Company Description",
                "placeholder" => "Company Description",
            ],
            "schedule" => [
                "field" => "date-field",
                "label" => "Start time",
                "placeholder" => "Company Address",
            ],

            "company_address" => [
                "field" => "text-field",
                "label" => "Company Address",
                "placeholder" => "Company Address",
            ],
            "company_telephone" => [
                "field" => "text-field",
                "label" => "Company Telephone",
                "placeholder" => "Company Telephone",
            ],
            "company_url" => [
                "field" => "text-field",
                "label" => "Web URL",
                "placeholder" => "http://example.com",
            ],
            "company_email" => [
                "field" => "text-field",
                "label" => "Company Email",
                "placeholder" => "Company Email",
            ],
            "company_logo" => [
                "field" => "image-field",
                "label" => "Company Logo",
                "placeholder" => "Company Logo",
            ]
        ]
    ],
    "email_server" => [
        "setting" => [
            "label" => "Email Server",
            "class" => "form-control",
        ],
        "general" => [
            "setting" => [
                "label" => "General Email",
                "class" => "form-control",
                "code" => "general"
            ],
            "enabled" => [
                "field" => "boolean-field",
                "label" => "Enabled",
                "placeholder" => "Enabled",
            ],
            "host" => [
                "field" => "text-field",
                "label" => "Host Name",
                "placeholder" => "Hostname",
            ],
            "security" => [
                "field" => "text-field",
                "label" => "Security",
                "placeholder" => "Security",
            ],
            "username" => [
                "field" => "text-field",
                "label" => "Username",
                "placeholder" => "Username",
            ],
            "port" => [
                "field" => "text-field",
                "label" => "Port",
                "placeholder" => "Port",
            ],
            "password" => [
                "field" => "password-field",
                "label" => "Password",
                "placeholder" => "Password",
            ],
        ]
    ],
    "customer" => [
        "setting" => [
            "label" => "Customer",
            "class" => "form-control",
        ],
        "general" => [
            "setting" => [
                "label" => "General Customer",
                "class" => "form-control",
                "code" => "general"
            ],
            "allow_login" => [
                "field" => "boolean-field",
                "label" => "Allow login",
                "placeholder" => "Allow login",
            ],
            "allow_payment_as_a_guest" => [
                "field" => "boolean-field",
                "label" => "Allow Payment As A Guest",
                "placeholder" => "Allow Payment As A Guest",
            ],
            "send_welcome_email" => [
                "field" => "boolean-field",
                "label" => "Send Welcome Email",
                "placeholder" => "Send Welcome Email",
            ],
            "send_welcome_email_template" => [
                "field" => "select-field",
                "label" => "Send Welcome Email Template",
                "options" => \Ispgo\SettingsManager\Source\Config\EmailTemplate::class
            ],
            "email_confirmation_account_confirmation" => [
                "field" => "boolean-field",
                "label" => "Email Account Confirmation",
            ],
            "email_confirmation_account_template" => [
                "field" => "select-field",
                "label" => "Email confirmation Account Template",
                "options" => \Ispgo\SettingsManager\Source\Config\EmailTemplate::class
            ],
            "allow_requesting_a_new_service" => [
                "field" => "boolean-field",
                "label" => "Allow Requesting New Service",
                "placeholder" => "Allow Requesting New Service",
            ],
            "save_payment_methods" => [
                "field" => "boolean-field",
                "label" => "Save Payment Methods",
                "placeholder" => "Save Payment Methods",
            ],
            "allow_customer_registration" => [
                "field" => "boolean-field",
                "label" => "Allow Customer Registration",
                "placeholder" => "Allow Customer Registration",
            ],
        ],

        "security" => [
            "setting" => [
                "label" => "Recaptcha",
                "class" => "form-control",
                "code" => "general"
            ],
            "api_key" => [
                "field" => "text-field",
                "label" => "Api key",
                "placeholder" => "Api key",
            ],
            "show_in_sign_in" => [
                "field" => "boolean-field",
                "label" => "Show In Sign In",
                "placeholder" => "Show In Sign In",
            ],
            "show_in_sign_up" => [
                "field" => "boolean-field",
                "label" => "Show In Sign Up",
                "placeholder" => "Show In Sign Up",
            ]
        ]
    ],
    "invoice" => [
        "setting" => [
            "label" => "Invoices",
            "class" => "form-control",
        ],
        "general" => [
            "setting" => [
                "label" => "General Invoice",
                "class" => "form-control",
                "code" => "general"
            ],
            "enable_service_when_paying" => [
                "field" => "boolean-field",
                "label" => "Enable Service when paying",
            ],
            "enable_service_by_payment_promise" => [
                "field" => "boolean-field",
                "label" => "Enable service if a promise is created",
            ],
            "enable_partial_payment" => [
                "field" => "boolean-field",
                "label" => "Enable partial payment",
            ],
            "send_email_when_paying" => [
                "field" => "boolean-field",
                "label" => "Send Email when paying",
            ],
            "email_template_paying" => [
                "field" => "select-field",
                "label" => "Email template Paying",
                "options" => \Ispgo\SettingsManager\Source\Config\EmailTemplate::class
            ],
            "send_email_create_invoice" => [
                "field" => "boolean-field",
                "label" => "Send Email when creating invoice",
            ],
            "email_template_created_invoice" => [
                "field" => "select-field",
                "label" => "Send Email when creating invoice",
                "options" => \Ispgo\SettingsManager\Source\Config\EmailTemplate::class
            ],
            "attach_invoice" => [
                "field" => "boolean-field",
                "label" => "Attach PDF invoice",
            ],
        ]
    ],
    "payment" => [
        "setting" => [
            "label" => "Payment",
            "class" => "form-control",
        ],
        "payu" => [
            "setting" => [
                "label" => "PayU Settings",
                "class" => "form-control",
                "code" => "payu"
            ],
            "enabled" => [
                "field" => "boolean-field",
                "label" => "Enabled",
                "placeholder" => "Enabled",
            ],
            "api_key" => [
                "field" => "text-field",
                "label" => "API Key",
                "placeholder" => "API Key",
            ],
            "api_login" => [
                "field" => "text-field",
                "label" => "API Login",
                "placeholder" => "API Login",
            ],
            "merchant_id" => [
                "field" => "text-field",
                "label" => "Merchant ID",
                "placeholder" => "Merchant ID",
            ],
            "account_id" => [
                "field" => "text-field",
                "label" => "Account ID",
                "placeholder" => "Account ID",
            ],
            "url_confirmation" => [
                "field" => "text-field",
                "label" => "URL Confirmation",
                "placeholder" => "URL Confirmation",
            ],
            "url_response" => [
                "field" => "text-field",
                "label" => "URL Response",
                "placeholder" => "URL Response",
            ],
            "env" => [
                "field" => "select-field",
                "label" => "Environment",
                "placeholder" => "Environment",
                "options" => \App\Settings\Config\Sources\Environment::class,
            ],
        ],
        'wompi' => [
            'setting' => [
                'label' => 'Wompi Settings',
                'class' => 'form-control',
                'code' => 'wompi',
            ],
            'enabled' => [
                'field' => 'boolean-field',
                'label' => 'Enabled',
                'placeholder' => 'Enabled',
            ],
            "env" => [
                "field" => "select-field",
                "label" => "Environment",
                "placeholder" => "Environment",
                "options" => \App\Settings\Config\Sources\Environment::class,
            ],
            'public_key_sandbox' => [
                'field' => 'text-field',
                'label' => 'Public Key',
                'placeholder' => 'Public Key',
            ],
            'integrity_sandbox' => [
                'field' => 'text-field',
                'label' => 'Integrity',
                'placeholder' => 'Integrity',
            ],
            'public_key' => [
                'field' => 'text-field',
                'label' => 'Public Key',
                'placeholder' => 'Public Key',
            ],
            'integrity' => [
                'field' => 'text-field',
                'label' => 'Integrity',
                'placeholder' => 'Integrity',
            ],
            'url_status_sandbox' => [
                'field' => 'text-field',
                'label' => 'Status Url',
                'placeholder' => 'Status Url',
            ],
            'url_status' => [
                'field' => 'text-field',
                'label' => 'Status Url',
                'placeholder' => 'Status Url',
            ],
            'confirmation_url' => [
                'field' => 'text-field',
                'label' => 'Confirmation URL',
                'placeholder' => 'Confirmation URL',
            ],
        ],
    ],
];
