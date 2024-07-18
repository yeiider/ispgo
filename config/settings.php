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
];
