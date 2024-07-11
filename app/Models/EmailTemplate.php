<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class EmailTemplate extends Model
{
    use HasFactory;


    protected $fillable = [
        'name', 'subject', 'body', 'entity', 'is_active',
        'created_by', 'updated_by', 'test_email', 'description'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

}
