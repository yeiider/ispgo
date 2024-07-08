<?php

namespace App\Models\Services;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'download_speed', 'upload_speed', 'monthly_price','overage_fee',
        'data_limit', 'unlimited_data', 'contract_period', 'promotions', 'extras_included',
        'geographic_availability', 'promotion_start_date', 'promotion_end_date', 'plan_image',
        'customer_rating', 'customer_reviews', 'service_compatibility', 'network_priority',
        'technical_support', 'additional_benefits', 'connection_type', 'status','created_by', 'updated_by','modality_type','plan_type'
    ];
    protected $casts = [
        'promotion_end_date' => 'datetime',
        'promotion_start_date' => 'datetime'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_by = Auth::id();
            $model->updated_by = Auth::id();
        });

        static::updating(function ($model) {
            $model->updated_by = Auth::id();
        });
    }
}
