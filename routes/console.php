<?php

use App\Settings\GeneralProviderConfig;
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
$billingDate = GeneralProviderConfig::getBillingDate();
Schedule::command('invoice:generated_monthly')->monthlyOn($billingDate, '00:00');

// Programar el comando para suspender servicios basado en la fecha de corte
$cutOffDate = GeneralProviderConfig::getCutOffDate();
$currentMonth = now()->month;
$currentYear = now()->year;

// Si la fecha de corte es menor que la de facturación, prográmalo para el siguiente mes
if ($cutOffDate < $billingDate) {
    $scheduleMonth = ($currentMonth == 12) ? 1 : $currentMonth + 1;
    $scheduleYear = ($currentMonth == 12) ? $currentYear + 1 : $currentYear;
} else {
    $scheduleMonth = $currentMonth;
    $scheduleYear = $currentYear;
}

$cutOffDate = Carbon::create($scheduleYear, $scheduleMonth, $cutOffDate);

Schedule::command('smartolt:process_batches')->everyMinute();


Schedule::command('services:suspend_monthly')->monthlyOn($cutOffDate->day, '00:00');
Schedule::call(function () {
    $cutoffTime = Carbon::now()->subMinutes(\Ispgo\Wiivo\WiivoConfigProvider::getSessionLife());
    SessionChatBot::where('updated_at', '<', $cutoffTime)->delete();
})->everyMinute();
