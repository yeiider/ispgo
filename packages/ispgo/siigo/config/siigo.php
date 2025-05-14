<?php
return [
    'enabled'       => env('SIIGO_ENABLED', false),
    'environment'   => env('SIIGO_ENV', 'prod'),
    'base_url'      => env('SIIGO_BASE_URL', 'https://api.siigo.com/'),
    'username'      => env('SIIGO_USERNAME'),
    'access_key'    => env('SIIGO_ACCESS_KEY'),
    'partner_id'    => env('SIIGO_PARTNER_ID', null),
    'sync_customer' => env('SIIGO_SYNC_CUSTOMER', true),
    'sync_invoice'  => env('SIIGO_SYNC_INVOICE', true),
];
