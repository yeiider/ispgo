<?php

namespace App\Services\Oltgo;

use App\Helpers\ConfigHelper;
use App\Helpers\Notify;
use App\Models\Olt;
use App\Services\Telegram\SystemNotifier;
use App\Settings\Oltgo\SettingOltgo;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Ispgo\Wiivo\ServiceWiivo;
use Throwable;

/**
 * Convierte las alertas que envía OLTGO en avisos para el equipo técnico:
 * campana de ISPGO, Telegram y, si está configurado, WhatsApp.
 */
class OltgoAlertDispatcher
{
    /** Tipo de evento => [título, nivel, categoría de aviso]. Las caídas de ONUs sueltas no se avisan. */
    public const KINDS = [
        'port_mass_los' => ['Corte masivo de fibra', 'danger', 'oltgo'],
        'mass_power_loss' => ['Corte de energía en la zona', 'warning', 'oltgo'],
        'interface_down' => ['Puerto caído', 'danger', 'oltgo'],
        'card_changed' => ['Cambio en una tarjeta de la OLT', 'warning', 'oltgo'],
        'olt_unreachable' => ['OLT sin respuesta', 'danger', 'oltgo'],
        'olt_rebooted' => ['OLT reiniciada', 'danger', 'oltgo'],
        'olt_high_temperature' => ['Temperatura alta en la OLT', 'danger', 'oltgo'],
        'port_high_usage' => ['Puerto saturado', 'warning', 'oltgo_capacity'],
        'onus_signal_degrading' => ['ONUs perdiendo señal', 'warning', 'oltgo_signal'],
        'interface_up' => ['Puerto recuperado', 'success', 'oltgo_recovery'],
        'olt_reachable' => ['OLT responde de nuevo', 'success', 'oltgo_recovery'],
        'port_usage_normal' => ['Puerto sin saturación', 'success', 'oltgo_recovery'],
        'olt_temperature_normal' => ['Temperatura de la OLT normal', 'success', 'oltgo_recovery'],
    ];

    /**
     * @param array<int, array<string, mixed>> $events
     */
    public function dispatch(array $events): void
    {
        $events = array_values(array_filter($events, fn ($e) => isset(self::KINDS[$e['kind'] ?? ''])));
        if (!$events) {
            return;
        }

        $inApp = $this->setting('enabled') !== '0';
        $olts = Olt::pluck('name', 'id');
        $byCategory = [];
        $whatsapp = [];
        foreach ($events as $e) {
            [$title, $level, $category] = self::KINDS[$e['kind']];
            $message = $this->message($e, $olts[$e['olt_id']] ?? "OLT {$e['olt_id']}");

            // Las recuperaciones no van a la campana: se ven en el informe de la OLT.
            if ($inApp && $category !== 'oltgo_recovery') {
                Notify::notifyAdmins($title, $message, $level, "/olts/{$e['olt_id']}/reports", [
                    'source' => 'oltgo',
                    'kind' => $e['kind'],
                    'olt_id' => $e['olt_id'],
                    'port' => $e['port'] ?? null,
                    'at' => $e['at'],
                ]);
            }
            $byCategory[$category][] = ['line' => "• {$title}: {$message}", 'level' => $level];
            if ($category === 'oltgo' && in_array($e['severity'] ?? '', ['warning', 'critical'], true)) {
                $whatsapp[] = "*{$title}*\n{$message}";
            }
        }

        // Un solo mensaje por categoría y lote aunque un corte genere muchos eventos a la vez.
        foreach ($byCategory as $category => $items) {
            $n = count($items);
            $lines = array_column($items, 'line');
            $worst = array_reduce(array_column($items, 'level'), fn ($a, $b) => $this->worse($a, $b), 'success');
            SystemNotifier::send(
                $category,
                $n === 1 ? 'Alerta de red' : "{$n} alertas de red",
                implode("\n", array_slice($lines, 0, 30)) . ($n > 30 ? "\n… y " . ($n - 30) . ' más' : ''),
                $worst,
            );
        }

        if ($whatsapp && $this->setting('whatsapp_enabled')) {
            $this->sendWhatsapp(implode("\n\n", $whatsapp));
        }
    }

    private function worse(string $a, string $b): string
    {
        $rank = ['success' => 0, 'info' => 1, 'warning' => 2, 'danger' => 3];

        return ($rank[$b] ?? 0) > ($rank[$a] ?? 0) ? $b : $a;
    }

    private function message(array $e, string $olt): string
    {
        $when = Carbon::parse($e['at'])->timezone('America/Bogota')->format('d/m H:i');
        $parts = [$olt];
        if (!empty($e['port'])) {
            $parts[] = $e['port'];
        }
        $text = implode(' · ', $parts) . " ({$when})";
        if (!empty($e['detail'])) {
            $text .= ': ' . $e['detail'];
        } elseif (!empty($e['from_state']) && !empty($e['to_state'])) {
            $text .= ": {$e['from_state']} → {$e['to_state']}";
        }

        return $text;
    }

    private function sendWhatsapp(string $text): void
    {
        $numbers = array_filter(array_map(
            fn ($n) => preg_replace('/\D/', '', $n),
            explode(',', (string) $this->setting('whatsapp_numbers'))
        ));
        $wiivo = app(ServiceWiivo::class);
        foreach ($numbers as $n) {
            $phone = strlen($n) === 10 ? '57' . $n : $n;
            try {
                $wiivo->sendMessage(['phone' => $phone, 'message' => "Alerta de red ISPGO\n\n" . $text]);
            } catch (Throwable $e) {
                Log::warning("OLTGO alertas: WhatsApp a {$phone} falló: " . $e->getMessage());
            }
        }
    }

    private function setting(string $key): ?string
    {
        try {
            return ConfigHelper::getConfigValue(SettingOltgo::ALERTS_PATH . $key);
        } catch (Throwable) {
            return null;
        }
    }
}
