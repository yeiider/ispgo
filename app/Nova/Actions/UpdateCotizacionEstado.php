<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Http\Requests\NovaRequest;

class UpdateCotizacionEstado extends Action
{
    use Queueable;

    /**
     * El nombre de la acción.
     *
     * @return string
     */
    public function name()
    {
        return 'Actualizar Estado';
    }

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        $updated = 0;
        
        foreach ($models as $model) {
            $model->estado = $fields->estado;
            $model->save();
            $updated++;
        }

        $message = $updated === 1 
            ? 'El estado de la cotización ha sido actualizado exitosamente.' 
            : "El estado de {$updated} cotizaciones ha sido actualizado exitosamente.";

        return Action::message($message);
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            Select::make('Estado', 'estado')
                ->options([
                    'pendiente' => 'Pendiente',
                    'atendida' => 'Atendida',
                ])
                ->displayUsingLabels()
                ->rules('required')
                ->help('Selecciona el nuevo estado para las cotizaciones seleccionadas.'),
        ];
    }
}
