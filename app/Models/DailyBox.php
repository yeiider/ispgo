<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class DailyBox extends Model
{
    protected $fillable = [
        'box_id',
        'date',
        'start_amount',
        'end_amount',
        'transactions'
    ];

    protected $casts = [
        'date' => 'datetime',
    ];

    public function box()
    {
        return $this->belongsTo(Box::class);
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($dailyBox) {
            $today = Carbon::now()->format('Y-m-d');
            $exists = DailyBox::where('box_id', $dailyBox->box_id)
                ->where('date', $today)
                ->exists();
            if ($exists) {
                throw new \Exception('A DailyBox for this box already exists for today.');
            }
        });
    }

    public static function updateAmount($id, $newAmount)
    {
        $dailyBox = self::find($id);
        if (!$dailyBox) {
            throw new \Exception('DailyBox not found.');
        }
        $dailyBox->end_amount+=$newAmount;
        $dailyBox->save();
        return $dailyBox;
    }
}
