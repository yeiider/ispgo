<?php

namespace App\Console\Commands;

use App\Models\Finance\CashRegister;
use App\Models\Finance\CashRegisterClosure;
use App\Jobs\ProcessCashRegisterClosure;
use App\Settings\FinanceProviderConfig;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class AutoCloseCashRegisters extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ispgo:auto-close-cash-registers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cierra automáticamente las cajas que quedaron abiertas según el horario configurado.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Iniciando proceso de cierre automático de cajas...');

        if (!FinanceProviderConfig::isAutoCloseEnabled()) {
            $this->warn('El cierre automático de cajas está desactivado en la configuración.');
            return;
        }

        $autoCloseTime = FinanceProviderConfig::getAutoCloseTime();
        $this->info("Hora configurada para cierre: {$autoCloseTime}");

        // Obtener todas las cajas abiertas
        $openRegisters = CashRegister::where('status', CashRegister::STATUS_OPEN)->get();

        if ($openRegisters->isEmpty()) {
            $this->info('No hay cajas abiertas para cerrar.');
            return;
        }

        $count = 0;
        foreach ($openRegisters as $register) {
            try {
                // Si la caja ya tiene un cierre procesándose para hoy, omitir
                $today = Carbon::today();
                $existingClosure = CashRegisterClosure::where('cash_register_id', $register->id)
                    ->whereDate('closure_date', $today)
                    ->first();

                if ($existingClosure && $existingClosure->status === CashRegisterClosure::STATUS_PROCESSING) {
                    $this->line("Caja {$register->id} ({$register->name}) ya tiene un cierre en proceso. Omitiendo.");
                    continue;
                }

                // Despachar el cierre. Como es automático, usamos el balance esperado como cierre.
                // Usamos el usuario ID 1 o el configurado como por defecto si existe.
                $adminId = 1; // Fallback

                ProcessCashRegisterClosure::dispatch(
                    $register->id,
                    $register->user_id ?? $adminId,
                    $today,
                    null, // El job calculará el balance esperado
                    'Cierre automático por el sistema'
                )->onQueue('redis');

                $this->info("Cierre programado para caja ID {$register->id}: {$register->name}");
                $count++;
            } catch (\Exception $e) {
                Log::error("Error en cierre automático de caja ID {$register->id}: " . $e->getMessage());
                $this->error("Error cerrando caja ID {$register->id}: " . $e->getMessage());
            }
        }

        $this->info("Se han programado {$count} cierres de caja.");
    }
}
