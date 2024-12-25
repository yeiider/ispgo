<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HtmlTemplate extends Model
{
    protected $fillable = [
        'name',
        'body',
        'entity',
    ];
}
