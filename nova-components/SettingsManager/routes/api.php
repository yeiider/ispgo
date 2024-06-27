<?php


use Illuminate\Http\Request;

Route::get('/settings', [\Ispgo\SettingsManager\Http\Controller\Settings::class,'settings']);

Route::post('/settings', function (Request $request) {
    return Setting::updateOrCreate(
        ['section' => $request->section, 'group' => $request->group, 'key' => $request->key],
        ['value' => $request->value]
    );
});
