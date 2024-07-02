<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type',
        'invoice_notice_type',
        'create_invoice_day',
        'create_invoice_time',
        'payment_day',
        'payment_reminder',
        'send_push_notifications',
        'cutoff_day',
        'suspend_service',
        'taxes',
        'automatic_cut',
        'automatic_invoice',
        'screen_notice',
        'payment_reminder_notice',
        'receive_cut_email',
        'receive_invoice_email',
        'receive_notice_email',
    ];
}
