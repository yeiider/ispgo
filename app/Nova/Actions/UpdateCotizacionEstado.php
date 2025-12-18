<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Textarea;
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
            
            // Solo actualizar notas si se proporcionó y es una sola cotización
            if ($models->count() === 1 && !empty($fields->notas)) {
                $model->notas = $fields->notas;
            }
            
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
        $fields = [
            Select::make('Estado', 'estado')
                ->options([
                    'pendiente' => 'Pendiente',
                    'atendida' => 'Atendida',
                    'cancelada' => 'Cancelada',
                    'no_contactado' => 'No Contactado',
                    'completada' => 'Completada',
                ])
                ->displayUsingLabels()
                ->rules('required')
                ->help('Selecciona el nuevo estado para las cotizaciones seleccionadas.'),
        ];

        // Verificar si solo se seleccionó un recurso
        // Nova pasa los recursos de diferentes formas:
        // - Desde el índice: como string separado por comas o como array en 'resources'
        // - Desde el detalle: como 'resourceId' único
        $resources = $request->input('resources', '');
        $resourceId = $request->input('resourceId');
        
        if (!empty($resources)) {
            // Si resources es un array, usarlo directamente
            if (is_array($resources)) {
                $resourceIds = array_filter($resources);
                $isSingleResource = count($resourceIds) === 1;
            } 
            // Si resources es un string, separarlo por comas
            elseif (is_string($resources)) {
                $resourceIds = array_filter(explode(',', $resources));
                $isSingleResource = count($resourceIds) === 1;
            } else {
                $isSingleResource = false;
            }
        } elseif (!empty($resourceId)) {
            // Acción desde la página de detalle (siempre es un solo recurso)
            $isSingleResource = true;
        } else {
            // Si no hay información, asumimos que puede ser múltiple
            $isSingleResource = false;
        }
        
        // Si solo hay un recurso seleccionado, agregar el campo de notas
        if ($isSingleResource) {
            $fields[] = Textarea::make('Notas', 'notas')
                ->nullable()
                ->help('Opcional: Agrega notas adicionales para esta cotización.')
                ->rows(3);
        }

        return $fields;
    }
}
