<?php

namespace App\Nova\Actions\Invoice;

use App\Jobs\SendInvoicesNotificationBatch;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Actions\ActionResponse;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;

class NotifyAllInvoices extends Action implements ShouldQueue
{
    use InteractsWithQueue;
    use Queueable;

    public function __construct()
    {
        $this->connection = 'redis';
        $this->queue = 'redis';
    }

    public function name(): string
    {
        return __('Enviar notificaciones de facturas (por rango/estado)');
    }


    /**
     * Perform the action on the given models.
     *
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models): mixed
    {
        $start = $fields->get('start_date');
        $end = $fields->get('end_date');
        $status = $fields->get('status') ?: 'all';

        // Dispatch the batch job with a 1-minute delay
        SendInvoicesNotificationBatch::dispatch($start, $end, $status)
            ->delay(now()->addMinute())->onQueue('redis');

        return Action::message(__('La acción fue programada. Se enviarán las facturas en 1 minuto.'));
    }

    /**
     * Get the fields available on the action.
     *
     * @return array<int, Field>
     */
    public function fields(NovaRequest $request): array
    {
        return [
            Date::make(__('Fecha inicio'), 'start_date')
                ->help(__('Opcional. Filtra por fecha de emisión desde.')),

            Date::make(__('Fecha fin'), 'end_date')
                ->help(__('Opcional. Filtra por fecha de emisión hasta.')),

            Select::make(__('Estado'), 'status')
                ->options([
                    'all' => __('Todos'),
                    'paid' => __('Pagadas'),
                    'unpaid' => __('No pagadas'),
                    'overdue' => __('Vencidas'),
                    'canceled' => __('Canceladas'),
                ])
                ->displayUsingLabels()
                ->default('all')
                ->help(__('Seleccione el estado de las facturas a notificar.')),
        ];
    }
}
