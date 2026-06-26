<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiRequestLog extends Model
{
    protected $fillable = [
        'ip_address',
        'user_agent',
        'method',
        'url',
        'query_params',
        'request_body',
        'response_status',
        'response_body',
    ];

    protected $casts = [
        'query_params' => 'array',
        'request_body' => 'array',
        'response_body' => 'array',
    ];
}
