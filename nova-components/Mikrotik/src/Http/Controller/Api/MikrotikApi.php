<?php

namespace Ispgo\Mikrotik\Http\Controller\Api;

use App\Http\Controllers\Controller;
use App\Models\Services\Plan;
use Illuminate\Http\Request;

class MikrotikApi extends Controller
{

    public function getPlans(Request $request): \Illuminate\Http\JsonResponse
    {
        $plans = Plan::all();
        return response()->json(["data" => $plans]);
    }
}
