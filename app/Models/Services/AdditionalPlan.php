<?php

namespace App\Models\Services;

use App\Models\Services\Service;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdditionalPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'monthly_price',
        'status',
        'description'
    ];

    protected $casts = [
        'monthly_price' => 'float',
    ];

    /**
     * Services that have this additional plan.
     */
    public function services()
    {
        return $this->belongsToMany(Service::class, 'service_additional_plan');
    }
}
