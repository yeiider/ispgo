<?php

use Illuminate\Support\Facades\Route;

Route::get('/handleWebhook', [\Ispgo\Wiivo\Http\Controllers\Wiivo::class, 'handleWebhook'])->name('wiivo.webhook');
