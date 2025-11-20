<?php

namespace App\Console\Commands;

use App\Models\Invoice\PaymentPromise;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class HandleExpiredPaymentPromises extends Command
{
    protected $signature = 'payment-promises:handle-expired';

    protected $description = 'Suspende servicios y marca promesas vencidas que no han sido cumplidas.';

    public function handle(): int
    {
        $now = Carbon::now()->startOfDay();

        $promises = PaymentPromise::with(['invoice.service'])
            ->where('status', 'pending')
            ->whereDate('promise_date', '<', $now)
            ->get();

        if ($promises->isEmpty()) {
            $this->info('No se encontraron promesas de pago vencidas.');
            return Command::SUCCESS;
        }

        $processed = 0;

        foreach ($promises as $promise) {
            $invoice = $promise->invoice;

            if (!$invoice) {
                continue;
            }

            // Si la factura ya no tiene saldo pendiente, asumimos que el pago se realizó.
            if ((float) $invoice->outstanding_balance <= 0) {
                continue;
            }

            $service = $invoice->service;

            if ($service && $service->service_status !== 'suspended') {
                $service->suspend();
            }

            $promise->status = 'cancelled';
            $promise->save();

            $processed++;
        }

        $message = "Promesas de pago vencidas procesadas: {$processed}.";
        $this->info($message);
        Log::info($message);

        return Command::SUCCESS;
    }
}
