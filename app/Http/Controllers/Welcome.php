<?php

namespace App\Http\Controllers;

use App\Models\Services\Plan;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Traits\EnumeratesValues;
use Inertia\Inertia;

class Welcome extends Controller
{

    public function index(): \Inertia\Response
    {
        return Inertia::render('Welcome', [
            'loginUrl' => route('customer.login'),
            'registerUrl' => route('customer.register'),
            'plans' => $this->getPlans(),
        ]);
    }

    /**
     * @return Collection|EnumeratesValues
     */
    private function getPlans(): Collection|EnumeratesValues
    {
        return Plan::query()
            ->where('status', 1) // Solo planes activos
            ->where(function ($query) {
                $query
                    ->where(function ($subQuery) {
                        $subQuery
                            ->whereNotNull('promotion_start_date')
                            ->whereNotNull('promotion_end_date')
                            ->where('promotion_start_date', '<=', now())
                            ->where('promotion_end_date', '>=', now());
                    })
                    ->orWhere(function ($subQuery) {
                        $subQuery
                            ->whereNull('promotion_start_date')
                            ->whereNull('promotion_end_date');
                    });
            })
            ->get();
    }
}
