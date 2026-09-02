<?php

use Ispgo\Mikrotik\Settings\SettingMikrotik;
use Ispgo\Siigo\Settings\SettingSiigo;
use Ispgo\Smartolt\Settings\SettingSmartolt;

return [
    "general" => [
        "setting" => [
            "label" => "Ajustes Generales",
            "class" => "form-control",
        ],
        "general" => [
            "setting" => [
                "label" => "Información General",
                "code" => "general"
            ],
            "company_name" => [
                "field" => "text-field",
                "label" => "Nombre de la Empresa",
                "placeholder" => "Nombre de la Empresa",
            ],
            "company_description" => [
                "field" => "textarea-field",
                "label" => "Descripción de la Empresa",
                "placeholder" => "Descripción de la Empresa",
            ],

            "company_address" => [
                "field" => "text-field",
                "label" => "Dirección de la Empresa",
                "placeholder" => "Dirección de la Empresa",
            ],
            "company_telephone" => [
                "field" => "text-field",
                "label" => "Teléfono de la Empresa",
                "placeholder" => "Teléfono de la Empresa",
            ],
            "company_url" => [
                "field" => "text-field",
                "label" => "URL Web",
                "placeholder" => "http://example.com",
            ],
            "company_email" => [
                "field" => "text-field",
                "label" => "Correo Electrónico de la Empresa",
                "placeholder" => "Correo Electrónico de la Empresa",
            ],
            "company_logo" => [
                "field" => "image-field",
                "label" => "Logo de la Empresa",
                "placeholder" => "Logo de la Empresa",
            ]
        ],
        "billing_cycle" => [
            "setting" => [
                "label" => "Configuración del Ciclo de Facturación",
                "code" => "billing_cycle"
            ],
            "billing_mode" => [
                "field" => "select-field",
                "label" => "Modo de Facturación",
                "options" => \App\Settings\Config\Sources\BillingMode::class
            ],
            "manageable_billing_cycle" => [
                "field" => "boolean-field",
                "label" => "Ciclo de Facturación Administrable",
            ],
            "billing_date" => [
                "field" => "select-field",
                "label" => "Día de Facturación",
                "options" => \App\Settings\Config\Sources\DaysOfMonth::class
            ],
            "cut_off_date" => [
                "field" => "select-field",
                "label" => "Día de Suspensión (Corte)",
                "options" => \App\Settings\Config\Sources\DaysOfMonth::class
            ],
            "payment_due_date" => [
                "field" => "select-field",
                "label" => "Día Límite de Pago",
                "options" => \App\Settings\Config\Sources\DaysOfMonth::class
            ],

            "automatic_cut_off" => [
                "field" => "boolean-field",
                "label" => "Corte Automático",
            ],
            "automatic_invoice_generation" => [
                "field" => "boolean-field",
                "label" => "Generación Automática de Facturas",
            ],
            "send_payment_reminders" => [
                "field" => "boolean-field",
                "label" => "Enviar Recordatorios de Pago",
            ],
            "late_fee_percentage" => [
                "field" => "text-field",
                "label" => "Porcentaje de Recargo por Mora",
                "placeholder" => "Ej. 5",
            ],
            "grace_period_days" => [
                "field" => "text-field",
                "label" => "Días de Período de Gracia",
                "placeholder" => "Ej. 3",
            ],
            "default_user" => [
                "field" => "select-field",
                "label" => "Usuario por Defecto",
                "placeholder" => "Usuario por Defecto",
                "options" => \App\Settings\Config\Sources\Users::class

            ]
        ]
    ],
    "customer" => [
        "setting" => [
            "label" => "Clientes",
            "class" => "form-control",
        ],
        "general" => [
            "setting" => [
                "label" => "Configuración General de Clientes",
                "class" => "form-control",
                "code" => "general"
            ],
            "allow_login" => [
                "field" => "boolean-field",
                "label" => "Permitir inicio de sesión",
                "placeholder" => "Permitir inicio de sesión",
            ],
            "allow_payment_as_a_guest" => [
                "field" => "boolean-field",
                "label" => "Permitir pago como invitado",
                "placeholder" => "Permitir pago como invitado",
            ],
            "send_welcome_email" => [
                "field" => "boolean-field",
                "label" => "Enviar correo de bienvenida",
                "placeholder" => "Enviar correo de bienvenida",
            ],
            "send_welcome_email_template" => [
                "field" => "select-field",
                "label" => "Plantilla de correo de bienvenida",
                "options" => \Ispgo\SettingsManager\Source\Config\EmailTemplate::class
            ],
            "email_confirmation_account_confirmation" => [
                "field" => "boolean-field",
                "label" => "Confirmación de cuenta por correo",
            ],
            "email_confirmation_account_template" => [
                "field" => "select-field",
                "label" => "Plantilla de confirmación de cuenta",
                "options" => \Ispgo\SettingsManager\Source\Config\EmailTemplate::class
            ],
            "allow_requesting_a_new_service" => [
                "field" => "boolean-field",
                "label" => "Permitir solicitar nuevo servicio",
                "placeholder" => "Permitir solicitar nuevo servicio",
            ],
            "save_payment_methods" => [
                "field" => "boolean-field",
                "label" => "Guardar métodos de pago",
                "placeholder" => "Guardar métodos de pago",
            ],
            "allow_customer_registration" => [
                "field" => "boolean-field",
                "label" => "Permitir registro de clientes",
                "placeholder" => "Permitir registro de clientes",
            ],
            "require_fiscal_info" => [
                "field" => "boolean-field",
                "label" => "Requerir información fiscal al crear cliente",
                "placeholder" => "Requerir información fiscal al crear cliente",
            ],
        ],

        "security" => [
            "setting" => [
                "label" => "Configuración de ReCAPTCHA",
                "class" => "form-control",
                "code" => "general"
            ],
            "api_key" => [
                "field" => "text-field",
                "label" => "Clave API de ReCAPTCHA",
                "placeholder" => "Clave API",
            ],
            "show_in_sign_in" => [
                "field" => "boolean-field",
                "label" => "Mostrar en inicio de sesión",
                "placeholder" => "Mostrar en inicio de sesión",
            ],
            "show_in_sign_up" => [
                "field" => "boolean-field",
                "label" => "Mostrar en registro",
                "placeholder" => "Mostrar en registro",
            ]
        ]
    ],


    "service" => [
        "setting" => [
            "label" => "Servicios",
            "class" => "form-control",
        ],
        "general" => [
            "setting" => [
                "label" => "Configuración General de Servicios",
                "class" => "form-control",
                "code" => "general"
            ],
            "create_installation_order" => [
                "field" => "boolean-field",
                "label" => "Crear orden de instalación al crear servicio",
                "placeholder" => "Crear orden de instalación",
            ],
            "notify_user_service_creation" => [
                "field" => "boolean-field",
                "label" => "Notificar al usuario al crear servicio",
                "placeholder" => "Notificar al usuario al crear servicio",
            ],
            "show_services_in_customer_section" => [
                "field" => "boolean-field",
                "label" => "Mostrar servicios en portal del cliente",
                "placeholder" => "Mostrar servicios en portal del cliente",
            ],
        ],
        "contract" => [
            "setting" => [
                "label" => "Contratos",
                "class" => "form-control",
                "code" => "contract"
            ],
            "enabled" => [
                "field" => "boolean-field",
                "label" => "Habilitar uso de contratos",
                "placeholder" => "Habilitar uso de contratos",
            ],
            "contract_template" => [
                "field" => "select-field",
                "label" => "Plantilla de contrato",
                "options" => \App\Settings\Config\Sources\HtmlTemplate::class
            ],

            "representative_signature" => [
                "field" => "image-field",
                "label" => "Firma del representante legal",
                "placeholder" => "Subir Firma",
                "accept" => "image/*", // Limits file types to images
            ],
            "representative_name" => [
                "field" => "text-field",
                "label" => "Nombre del representante",
                "placeholder" => "Ingrese el nombre del representante",
            ],
            "representative_document" => [
                "field" => "text-field",
                "label" => "Documento del representante",
                "placeholder" => "Ingrese el documento del representante",
            ],
            "representative_role" => [
                "field" => "text-field",
                "label" => "Cargo del representante",
                "placeholder" => "Ingrese el cargo del representante",
            ],
            "email_template_send" => [
                "field" => "select-field",
                "label" => "Plantilla de correo para enviar link de contrato",
                "options" => \Ispgo\SettingsManager\Source\Config\EmailTemplate::class
            ],
            "email_template_signed" => [
                "field" => "select-field",
                "label" => "Plantilla de correo para notificar contrato firmado",
                "options" => \Ispgo\SettingsManager\Source\Config\EmailTemplate::class
            ],
            "email_template_approved" => [
                "field" => "select-field",
                "label" => "Plantilla de correo para notificar contrato aprobado",
                "options" => \Ispgo\SettingsManager\Source\Config\EmailTemplate::class
            ],
            "email_template_rejected" => [
                "field" => "select-field",
                "label" => "Plantilla de correo para notificar contrato rechazado",
                "options" => \Ispgo\SettingsManager\Source\Config\EmailTemplate::class
            ]
        ],
    ],

    "invoice" => [
        "setting" => [
            "label" => "Facturas",
            "class" => "form-control",
        ],
        "general" => [
            "setting" => [
                "label" => "Facturación General",
                "class" => "form-control",
                "code" => "general"
            ],
            "enable_service_when_paying" => [
                "field" => "boolean-field",
                "label" => "Habilitar servicio al pagar",
            ],
            "enable_service_by_payment_promise" => [
                "field" => "boolean-field",
                "label" => "Habilitar servicio al crear promesa de pago",
            ],
            "enable_partial_payment" => [
                "field" => "boolean-field",
                "label" => "Habilitar pago parcial",
            ],
            "send_email_when_paying" => [
                "field" => "boolean-field",
                "label" => "Enviar correo al pagar",
            ],
            "email_template_paying" => [
                "field" => "select-field",
                "label" => "Plantilla de correo al pagar",
                "options" => \Ispgo\SettingsManager\Source\Config\EmailTemplate::class
            ],
            "send_email_create_invoice" => [
                "field" => "boolean-field",
                "label" => "Enviar correo al crear factura",
            ],
            "email_template_created_invoice" => [
                "field" => "select-field",
                "label" => "Plantilla de correo al crear factura",
                "options" => \Ispgo\SettingsManager\Source\Config\EmailTemplate::class
            ],
            "bcc_invoice_to" => [
                "field" => "text-field",
                "label" => "Copia oculta (BCC) de factura a",
                "placeholder" => "Copia oculta (BCC) de factura a",
            ],
            "attach_invoice" => [
                "field" => "boolean-field",
                "label" => "Adjuntar factura en PDF",
            ],
            "skip_invoice_if_suspended_and_unpaid" => [
                "field" => "boolean-field",
                "label" => "Omitir generación si el servicio está suspendido y la última factura está vencida",
            ],
            "enable_router_rental" => [
                "field" => "boolean-field",
                "label" => "Habilitar cobro por alquiler de router",
            ],
            "router_rental_amount" => [
                "field" => "text-field",
                "label" => "Valor de alquiler de router",
                "placeholder" => "Ingrese el valor de alquiler",
            ],
            "router_rental_name" => [
                "field" => "text-field",
                "label" => "Nombre del ítem de alquiler",
                "placeholder" => "Ingrese el nombre (ej. Alquiler de Router)",
            ],
        ]
    ],
    "payment" => [
        "setting" => [
            "label" => "Pasarelas de Pago",
            "class" => "form-control",
        ],
        "payu" => [
            "setting" => [
                "label" => "Configuración de PayU",
                "class" => "form-control",
                "code" => "payu"
            ],
            "payu-enabled" => [
                "field" => "boolean-field",
                "label" => "Habilitado",
                "placeholder" => "Habilitado",
            ],
            "api_key" => [
                "field" => "text-field",
                "label" => "Llave API (API Key)",
                "placeholder" => "Llave API",
            ],
            "api_login" => [
                "field" => "text-field",
                "label" => "Inicio de sesión API (API Login)",
                "placeholder" => "Inicio de sesión API",
            ],
            "merchant_id" => [
                "field" => "text-field",
                "label" => "ID de Comercio (Merchant ID)",
                "placeholder" => "ID de Comercio",
            ],
            "account_id" => [
                "field" => "text-field",
                "label" => "ID de Cuenta (Account ID)",
                "placeholder" => "ID de Cuenta",
            ],
            "url_confirmation" => [
                "field" => "text-field",
                "label" => "URL de Confirmación",
                "placeholder" => "URL de Confirmación",
            ],
            "url_response" => [
                "field" => "text-field",
                "label" => "URL de Respuesta",
                "placeholder" => "URL de Respuesta",
            ],
            ".env" => [
                "field" => "select-field",
                "label" => "Entorno",
                "placeholder" => "Entorno",
                "options" => \App\Settings\Config\Sources\Environment::class,
            ],
        ],
        'wompi' => [
            'setting' => [
                'label' => 'Configuración de Wompi',
                'class' => 'form-control',
                'code' => 'wompi',
            ],
            'wompi-enabled' => [
                'field' => 'boolean-field',
                'label' => 'Habilitado',
                'placeholder' => 'Habilitado',
            ],
            ".env" => [
                "field" => "select-field",
                "label" => "Entorno",
                "placeholder" => "Entorno",
                "options" => \App\Settings\Config\Sources\Environment::class,
            ],
            'public_key_sandbox' => [
                'field' => 'text-field',
                'label' => 'Llave Pública Sandbox',
                'placeholder' => 'Llave Pública Sandbox',
            ],
            'public_key' => [
                'field' => 'text-field',
                'label' => 'Llave Pública Producción',
                'placeholder' => 'Llave Pública Producción',
            ],
            'private_key_sandbox' => [
                'field' => 'text-field',
                'label' => 'Llave Privada Sandbox',
                'placeholder' => 'Llave Privada Sandbox',
            ],
            'private_key' => [
                'field' => 'text-field',
                'label' => 'Llave Privada Producción',
                'placeholder' => 'Llave Privada Producción',
            ],
            'integrity_sandbox' => [
                'field' => 'text-field',
                'label' => 'Firma de Integridad Sandbox',
                'placeholder' => 'Firma de Integridad Sandbox',
            ],

            'integrity' => [
                'field' => 'text-field',
                'label' => 'Firma de Integridad Producción',
                'placeholder' => 'Firma de Integridad Producción',
            ],

            'event_secret_sandbox' => [
                'field' => 'text-field',
                'label' => 'Secreto de Eventos Sandbox',
                'placeholder' => 'Secreto de Eventos Sandbox',
            ],
            'event_secret' => [
                'field' => 'text-field',
                'label' => 'Secreto de Eventos Producción',
                'placeholder' => 'Secreto de Eventos Producción',
            ],

            'confirmation_url' => [
                'field' => 'text-field',
                'label' => 'URL de Confirmación',
                'placeholder' => 'URL de Confirmación',
            ],
        ],
    ],

    "notifications" => [
        "setting" => [
            "label" => "Notificaciones",
            "class" => "form-control",
        ],
        "email_settings" => [
            "setting" => [
                "label" => "Configuración de Correo General",
                "class" => "form-control",
                "code" => "email_settings"
            ],
            "host" => [
                "field" => "text-field",
                "label" => "Servidor SMTP (Host)",
                "placeholder" => "Servidor SMTP",
            ],
            "security" => [
                "field" => "select-field",
                "label" => "Seguridad",
                "placeholder" => "Seguridad",
                "options" => \App\Settings\Config\Sources\EmailSecurity::class,
            ],
            "username" => [
                "field" => "text-field",
                "label" => "Usuario SMTP",
                "placeholder" => "Usuario SMTP",
            ],
            "port" => [
                "field" => "text-field",
                "label" => "Puerto SMTP",
                "placeholder" => "Puerto SMTP",
            ],
            "password" => [
                "field" => "password-field",
                "label" => "Contraseña SMTP",
                "placeholder" => "Contraseña SMTP",
            ],
        ],
        "wiivo" => \Ispgo\Wiivo\SettingWiivo::getSetting()
    ],

    "support" => [
        "setting" => [
            "label" => "Soporte / Tickets",
            "class" => "form-control",
        ],
        "ticket_settings" => [
            "setting" => [
                "label" => "Configuración de Tickets",
                "class" => "form-control",
                "code" => "ticket_settings"
            ],
            "allow_create_ticket" => [
                "field" => "boolean-field",
                "label" => "Permitir a clientes crear tickets",
                "placeholder" => "Permitir a clientes crear tickets",
            ],
            "notify_client" => [
                "field" => "boolean-field",
                "label" => "Notificar al cliente",
                "placeholder" => "Notificar al cliente",
            ],
            "ticket_priority" => [
                "field" => "select-field",
                "label" => "Prioridad de ticket por defecto",
                "options" => \App\Settings\Config\Sources\TicketPriority::class,
            ],
            "ticket_status" => [
                "field" => "select-field",
                "label" => "Estado de ticket por defecto",
                "options" => \App\Settings\Config\Sources\TicketStatus::class,
            ],
            "notify_technician" => [
                "field" => "boolean-field",
                "label" => "Notificar al técnico",
                "placeholder" => "Notificar al técnico",
            ],
            "allow_client_close_ticket" => [
                "field" => "boolean-field",
                "label" => "Permitir a clientes cerrar tickets",
                "placeholder" => "Permitir a clientes cerrar tickets",
            ],
            "notify_by_email" => [
                "field" => "boolean-field",
                "label" => "Notificar por correo electrónico",
                "placeholder" => "Notificar por correo electrónico",
            ],
            "email_template_client" => [
                "field" => "select-field",
                "label" => "Plantilla para notificar al cliente",
                "options" => \Ispgo\SettingsManager\Source\Config\EmailTemplate::class
            ],
            "email_template_technician" => [
                "field" => "select-field",
                "label" => "Plantilla para notificar al técnico",
                "options" => \Ispgo\SettingsManager\Source\Config\EmailTemplate::class
            ],
            "email_template_change_status" => [
                "field" => "select-field",
                "label" => "Plantilla para cambio de estado",
                "options" => \Ispgo\SettingsManager\Source\Config\EmailTemplate::class
            ],
        ],
    ],

    "mikrotik" => [
        "setting" => [
            "label" => "Mikrotik",
            "class" => "form-control",
        ],
        "general" => SettingMikrotik::getGeneralSettings(),
        "router_connection" => SettingMikrotik::getRouterConnectionSettings(),
        "dhcp" => SettingMikrotik::getDhcpSettings(),
        "simple_queue" => SettingMikrotik::getSimpleQueueSettings(),
        "service_actions" => SettingMikrotik::getServiceActionsSettings(),
        "advanced" => SettingMikrotik::getAdvancedSettings(),
    ],
    "smartolt" => [
        "setting" => [
            "label" => "Smart Olt",
            "class" => "form-control",
        ],
        "general"    => SettingSmartolt::getGeneralSettings(),
        "activation" => SettingSmartolt::getActivationSettings(),
    ],

    "siigo" => [
        "setting" => [
            "label" => "Siigo",
            "class" => "form-control",
        ],
        "general"  => SettingSiigo::getGeneralSettings(),
        "api"      => SettingSiigo::getApiSettings(),
        "invoices" => SettingSiigo::getInvoiceSettings(),
        "vouchers" => SettingSiigo::getVoucherSettings(),
        "others"   => SettingSiigo::getOtherSettings(),
    ],

    "iptv" => [
        "setting" => [
            "label" => "IPTV XUI.one",
            "class" => "form-control",
        ],
        "general"    => \App\Settings\Iptv\SettingIptv::getGeneralSettings(),
        "activation" => \App\Settings\Iptv\SettingIptv::getActivationSettings(),
    ],

    // OnePay integration settings
    "onepay" => [
        "setting" => [
            "label" => "OnePay",
            "class" => "form-control",
        ],
        "general" => [
            "setting" => [
                "label" => "Ajustes de OnePay",
                "code" => "general"
            ],
            "onepay_enabled" => [
                "field" => "boolean-field",
                "label" => "Habilitar OnePay",
            ],
            "onepay_base_url" => [
                "field" => "text-field",
                "label" => "URL Base de OnePay",
                "placeholder" => "https://api.onepay.la/v1"
            ],
            "onepay_api_token" => [
                "field" => "password-field",
                "label" => "Token API de OnePay",
                "placeholder" => "Token Secreto"
            ],
            "onepay_auto_create_day" => [
                "field" => "select-field",
                "label" => "Día del mes para generar cargos automáticamente",
                "options" => \App\Settings\Config\Sources\DaysOfMonth::class
            ],
            "onepay_auto_remind_day" => [
                "field" => "select-field",
                "label" => "Día del mes para enviar recordatorios automáticamente",
                "options" => \App\Settings\Config\Sources\DaysOfMonth::class
            ],
        ],
    ],
    "finance" => [
        "setting" => [
            "label" => "Finanzas",
            "class" => "form-control",
        ],
        "cash_register" => [
            "setting" => [
                "label" => "Cierre Automático de Caja",
                "code" => "cash_register"
            ],
            "auto_close_enabled" => [
                "field" => "boolean-field",
                "label" => "Habilitar Cierre Automático",
            ],
            "auto_close_time" => [
                "field" => "time-field",
                "label" => "Hora de Cierre",
                "placeholder" => "23:59",
            ],
        ],
    ],
    "tables" => [
        "setting" => [
            "label" => "Configuración de Tablas",
            "class" => "form-control",
        ],
        "columns" => [
            "setting" => [
                "label" => "Visibilidad de Columnas",
                "code" => "columns"
            ],
            "clients" => [
                "field" => "text-field",
                "label" => "Columnas Clientes",
                "default" => "id,name,document,services,service_states,billing_status,status,created_at,actions",
            ],
            "services" => [
                "field" => "text-field",
                "label" => "Columnas Servicios",
                "default" => "id,name,status,created_at,actions",
            ],
            "invoices" => [
                "field" => "text-field",
                "label" => "Columnas Facturas",
                "default" => "id,increment_id,customer,total,outstanding_balance,status,created_at,due_date,actions",
            ],
            "cotizaciones" => [
                "field" => "text-field",
                "label" => "Columnas Cotizaciones",
                "default" => "id,name,email,phone,plan,status,created_at,actions",
            ],
        ],
    ],

    "asistente_yane" => [
        "setting" => [
            "label" => "Asistente IA",
            "class" => "form-control",
        ],
        "general" => [
            "setting" => [
                "label" => "Identidad del Asistente",
                "code" => "general"
            ],
            "nombre" => [
                "field" => "text-field",
                "label" => "Nombre del Asistente",
                "placeholder" => "Ej. Lady",
            ],
        ],
        "planes" => [
            "setting" => [
                "label" => "Planes de Internet (públicos)",
                "code" => "planes"
            ],
            "listado" => [
                "field" => "textarea-field",
                "label" => "Planes (uno por línea: nombre | download_mbps | upload_mbps | precio | tipo | descripción | beneficios)",
                "placeholder" => "Plan Ultra | 200 | 200 | $65.000 | regular | Ideal para navegar, correos y redes sociales |\nPlan Platino | 900 | 900 | $105.000 | regular | Ideal para teletrabajo y estudio | Soporte prioritario 24/7",
            ],
        ],
        "contactos" => [
            "setting" => [
                "label" => "Contactos y Enlaces",
                "code" => "contactos"
            ],
            "web_url" => [
                "field" => "text-field",
                "label" => "Sitio Web",
                "placeholder" => "https://raicesc.net",
            ],
            "email" => [
                "field" => "text-field",
                "label" => "Correo de contacto",
                "placeholder" => "contacto@raicesc.net",
            ],
            "payment_url" => [
                "field" => "text-field",
                "label" => "URL de pagos",
                "placeholder" => "https://www.raicesc.net/pagos",
            ],
        ],
        "cobertura" => [
            "setting" => [
                "label" => "Cobertura",
                "code" => "cobertura"
            ],
            "ciudades" => [
                "field" => "textarea-field",
                "label" => "Ciudades y zonas de cobertura (una por línea: Ciudad: zona1, zona2)",
                "placeholder" => "Cali: Ciudad Pacífica, Kachipay, Bochalema, Tierra Linda\nJamundí: Pangola, El Castillo\nSantander de Quilichao\nPuerto Tejada\nGuachené\nPadilla\nCaloto",
            ],
            "sinonimos" => [
                "field" => "textarea-field",
                "label" => "Barrios / sinónimos por ciudad (una por línea: Ciudad: barrio1, barrio2)",
                "placeholder" => "Cali: ciudad pacifica, tierra linda, bochalema, kachipay\nGuachené: El llano, Llano de taula, El Guabal",
            ],
        ],
        "oficinas" => [
            "setting" => [
                "label" => "Oficinas",
                "code" => "oficinas"
            ],
            "listado" => [
                "field" => "textarea-field",
                "label" => "Oficinas (una por línea: Ciudad: dirección)",
                "placeholder" => "Cali (Ciudad Pacífica): Carrera 121 # 42-93\nSantander de Quilichao: Calle 4 # 14-37",
            ],
        ],
        "costos_instalacion" => [
            "setting" => [
                "label" => "Costos de Instalación",
                "code" => "costos_instalacion"
            ],
            "listado" => [
                "field" => "textarea-field",
                "label" => "Costo de instalación por zona (una por línea: Zona: monto)",
                "placeholder" => "Cali (Ciudad Pacífica) y Jamundí: Gratis\nPuerto Tejada, Ciudad Amiga: $50.000",
            ],
        ],
        "faqs" => [
            "setting" => [
                "label" => "Preguntas Frecuentes",
                "code" => "faqs"
            ],
            "listado" => [
                "field" => "textarea-field",
                "label" => "FAQs (una por línea: Pregunta => Respuesta)",
                "placeholder" => "¿Cuánto tarda la instalación? => Menos de 48 horas\n¿Hay cláusula de permanencia? => No",
            ],
        ],
        "canales_tv" => [
            "setting" => [
                "label" => "Canales de TV",
                "code" => "canales_tv"
            ],
            "listado" => [
                "field" => "textarea-field",
                "label" => "Listado de canales (separados por coma)",
                "placeholder" => "A&E, AMC HD, DISCOVERY, ESPN, ...",
            ],
        ],
    ],
];
