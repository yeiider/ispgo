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

// Schedule the suspend_everyday command for router IDs 1 to 8 with 30-minute intervals
Schedule::command('services:suspend_everyday 1')->dailyAt('00:00');
Schedule::command('services:suspend_everyday 2')->dailyAt('00:30');
Schedule::command('services:suspend_everyday 3')->dailyAt('01:00');
Schedule::command('services:suspend_everyday 4')->dailyAt('01:30');
Schedule::command('services:suspend_everyday 5')->dailyAt('02:00');
Schedule::command('services:suspend_everyday 6')->dailyAt('02:30');
Schedule::command('services:suspend_everyday 7')->dailyAt('03:00');
Schedule::command('services:suspend_everyday 8')->dailyAt('03:30');

Schedule::command('payment-promises:handle-expired')->dailyAt('04:30');

// OnePay: run daily; the command itself checks the configured day in OnePaySettings
Schedule::command('onepay:auto-create-charges')->daily();

// Cierre automático de cajas
//Schedule::command('ispgo:auto-close-cash-registers')->dailyAt(\App\Settings\FinanceProviderConfig::getAutoCloseTime());
Schedule::command('backup:run')->dailyAt('23:00');
Schedule::command('backup:clean')->dailyAt('23:30');
//Schedule::call(function () {
//    $cutoffTime = Carbon::now()->subMinutes(\Ispgo\Wiivo\WiivoConfigProvider::getSessionLife());
//    SessionChatBot::where('updated_at', '<', $cutoffTime)->delete();
//})->everyMinute();
