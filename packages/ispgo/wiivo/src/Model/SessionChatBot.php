<?php

namespace Ispgo\Wiivo\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionChatBot extends Model
{
    use HasFactory;

    protected $table = 'session_chat_bots';
    protected $fillable = [
        'chat_id',
        'user_id',
        'current_option',
        'message_history',
        'interaction_history'
    ];

    protected $casts = [
        'interaction_history' => 'array'
    ];
}
