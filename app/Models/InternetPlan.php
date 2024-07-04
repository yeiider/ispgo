<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternetPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'download_speed', 'upload_speed', 'monthly_price',
        'data_limit', 'unlimited_data', 'contract_period', 'promotions', 'extras_included',
        'geographic_availability', 'promotion_start_date', 'promotion_end_date', 'plan_image',
        'customer_rating', 'customer_reviews', 'service_compatibility', 'network_priority',
        'technical_support', 'additional_benefits', 'connection_type', 'status'
    ];
}
