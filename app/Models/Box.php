<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Box extends Model
{
    protected $fillable = [
        'name',
        'users'
    ];

    protected $casts = [
        "users" => "array"
    ];

    public function dailyBoxes()
    {
        return $this->hasMany(DailyBox::class);
    }

    public function getUsersArrayAttribute()
    {
        return explode(',', $this->users);
    }

    /**
     * Get the DailyBox for today, only if the authenticated user is allowed.
     *
     * @return DailyBox|null
     */
    public function getTodayDailyBox(): ?DailyBox
    {
        $today = Carbon::now()->format('Y-m-d');
        $userId = Auth::id();
        if (in_array($userId, $this->users)) {
            return $this->dailyBoxes()->where('date', $today)->first();
        }
        return null;
    }


    /**
     * Get the box assigned to a specific user.
     *
     * @param int $userId
     * @return Box|null
     */
    public static function getUserBox($userId)
    {
        return self::where('users', 'LIKE', "%{$userId}%")->first();
    }
}
