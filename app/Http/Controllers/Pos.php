<?php

namespace App\Http\Controllers;

use App\Models\Box;
use App\Models\DailyBox;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class Pos extends Controller
{
    public function index(): \Inertia\Response
    {
        return Inertia::render('Pos/MainContentPos', $this->getConfig());
    }

    private function getConfig(): array
    {
        $user = Auth::user();

        $box = Box::getUserBox($user->id);
        $box->dailyBoxes();
        return [
            "cashier" => auth()->user()->name,
            'config' => [
                'currency' => config('nova.currency'),
                'currencySymbol' => '$',
                'box' => $box,
                'todayBox' => $box->getTodayDailyBox(),
            ]
        ];
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'box_id' => 'required|exists:boxes,id',
            'start_amount' => 'required|numeric|min:0',
        ]);

        $box = Box::find($request->box_id);

        $userId = (string)Auth::id();
        if (!in_array($userId, $box->users)) {
            return redirect()->with(['status' => 'User not authorized to create DailyBox for this box.'], 403);
        }

        $today = Carbon::now()->format('Y-m-d');
        $existingDailyBox = $box->dailyBoxes()->where('date', $today)->first();

        if ($existingDailyBox) {
            return redirect()->with(['status' => 'DailyBox already exists for today.'], 400);
        }

        $dailyBox = DailyBox::create([
            'box_id' => $box->id,
            'date' => $today,
            'start_amount' => $request->start_amount,
            'end_amount' => 0,
        ]);

        return redirect()
            ->route('admin.pos')
            ->with('status', __('Box create successfully.'));
    }
}
