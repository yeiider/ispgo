<?php

use Illuminate\Support\Facades\Route;
use Ispgo\SettingsManager\Http\Controller\Settings;

Route::get('/settings', [Settings::class, 'fields']);

Route::post('/settings/save', [Settings::class, 'saveSetting']);
Route::post('/settings/upload', [Settings::class, 'uploadFiles']);
Route::delete('/settings/deleteFile/{file}', [Settings::class, 'deleteFiles']);

