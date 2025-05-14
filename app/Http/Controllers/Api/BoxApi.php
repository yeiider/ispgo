<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Box;
use App\Models\DailyBox;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BoxApi extends Controller
{
    /**
     * Create a DailyBox for the current day.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function createDailyBox(Request $request)
    {
        // Validar la solicitud
        $request->validate([
            'box_id' => 'required|exists:boxes,id',
            'start_amount' => 'required|numeric|min:0',
        ]);

        // Obtener el box por ID
        $box = Box::find($request->box_id);

        $userId = (string) Auth::id();

        if (!in_array($userId, $box->users)) {
            return response()->json(['error' => 'User not authorized to create DailyBox for this box.'], 403);
        }

        // Verificar si ya existe una DailyBox para hoy
        $today = Carbon::now()->format('Y-m-d');
        $existingDailyBox = $box->dailyBoxes()->where('date', $today)->first();

        if ($existingDailyBox) {
            return response()->json(['error' => 'DailyBox already exists for today.'], 400);
        }

        // Crear una nueva DailyBox
        $dailyBox = DailyBox::create([
            'box_id' => $box->id,
            'date' => $today,
            'start_amount' => $request->start_amount,
            'end_amount' => 0, // Inicializamos end_amount a 0
        ]);

        return response()->json([
            'message' => 'DailyBox created successfully.',
            'dailyBox' => $dailyBox,
        ], 201);
    }
}
