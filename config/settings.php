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
                "options" => \Ispgo\SettingsManager\Source\Config\Yesno::getConfig()
            ],
            "company_type" => [
                "field" => "select-field",
                "label" => "Company Type",
                "placeholder" => "Company Type",
                "options" => \Ispgo\SettingsManager\Source\Config\CompanyType::getConfig()
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
    ]
];
