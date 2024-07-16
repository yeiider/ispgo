<?php

use Illuminate\Http\Request;
use Ispgo\SettingsManager\Http\Controller\Settings;

Route::get('/settings', [Settings::class, 'fields']);

Route::post('/settings/save', [Settings::class, 'saveSetting']);
