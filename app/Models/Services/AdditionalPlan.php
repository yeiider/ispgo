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
        'description',
        'is_taxable'
    ];

    protected $casts = [
        'monthly_price' => 'float',
        'is_taxable' => 'boolean',
    ];

    /**
     * Services that have this additional plan.
     */
    public function services()
    {
        return $this->belongsToMany(Service::class, 'service_additional_plan');
    }
}
