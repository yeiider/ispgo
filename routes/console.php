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
Schedule::command('billing:generate-invoices')->dailyAt('00:00');

Schedule::command('app:syncronizar-datos-onu')->dailyAt('01:00');

Schedule::command('services:suspend_everyday')->dailyAt('00:00');

Schedule::command('payment-promises:handle-expired')->dailyAt('00:30');

// OnePay: run daily; the command itself checks the configured day in OnePaySettings
Schedule::command('onepay:auto-create-charges')->daily();

//Schedule::call(function () {
//    $cutoffTime = Carbon::now()->subMinutes(\Ispgo\Wiivo\WiivoConfigProvider::getSessionLife());
//    SessionChatBot::where('updated_at', '<', $cutoffTime)->delete();
//})->everyMinute();
