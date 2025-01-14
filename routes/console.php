<?php

use Carbon\Carbon;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Ispgo\Wiivo\Model\SessionChatBot;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::command('calculate:daily_invoice_balances')->daily();

// Programar el comando basado en la configuración de facturación
Schedule::command('invoice:generate_everyday')->dailyAt('00:00');


Schedule::command('smartolt:process_batches')->everyMinute();

Schedule::command('services:suspend_everyday')->dailyAt('00:00');

Schedule::call(function () {
    $cutoffTime = Carbon::now()->subMinutes(\Ispgo\Wiivo\WiivoConfigProvider::getSessionLife());
    SessionChatBot::where('updated_at', '<', $cutoffTime)->delete();
})->everyMinute();
