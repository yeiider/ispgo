<?php


use App\Models\Setting;
use Illuminate\Http\Request;

Route::get('/settings', function (Request $request) {
    return Setting::all();
});

Route::post('/settings', function (Request $request) {
    return Setting::updateOrCreate(
        ['section' => $request->section, 'group' => $request->group, 'key' => $request->key],
        ['scope' => 3],
        ['value' => $request->value]
    );
});
