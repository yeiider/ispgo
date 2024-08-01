<?php

use Illuminate\Support\Facades\Route;

Route::post('/handleWebhook', [\Ispgo\Wiivo\Http\Controllers\Wiivo::class, 'handleWebhook'])->name('wiivo.webhook');
