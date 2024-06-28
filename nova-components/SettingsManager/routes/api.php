<?php


use Illuminate\Http\Request;

Route::get('/settings', [\Ispgo\SettingsManager\Http\Controller\Settings::class,'fields']);

Route::post('/settings/save', [\Ispgo\SettingsManager\Http\Controller\Settings::class,'saveSetting']);
