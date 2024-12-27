<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Number;

class Scope extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'is_active'];

    /*protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {

            $model->code = self::getNextCode();
        });
    }

    public static function getNextCode(): int
    {
        $lastCode = self::orderBy('code', 'desc')->first();
        $lastCodeValue = $lastCode ? intval($lastCode->code) : 0;
        return $lastCodeValue + 1;
    }*/
}
