<?php

namespace App\Nova\Actions;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\MultiSelect;
use Laravel\Nova\Http\Requests\NovaRequest;

class AssignmentTickets extends Action
{
    use InteractsWithQueue, Queueable;

    /**
     * Perform the action on the given models.
     *
     * @param ActionFields $fields
     * @param Collection $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        foreach ($models as $model) {
            try {
                // Verifica si se seleccionaron técnicos
                if (empty($fields->technicians)) {
                    return Action::danger('No technicians selected.');
                }

                // Asigna los técnicos al ticket
                $model->assignUsers($fields->technicians);
            } catch (\Exception $e) {
                // Manejo de excepciones y mensaje de error
                return Action::danger("Failed to assign technicians to ticket ID {$model->id}: " . $e->getMessage());
            }
        }

        return Action::message('Technicians assigned successfully to all selected tickets.');
    }

    /**
     * Get the fields available on the action.
     *
     * @param NovaRequest $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        // Obtiene una lista de técnicos con sus nombres e IDs
        $technicians = User::all()->pluck('name', 'id');

        return [
            MultiSelect::make('Technicians')
                ->options($technicians)
                ->displayUsingLabels()
                ->rules('required')
                ->help('Select technicians to assign to the selected tickets.'),
        ];
    }
}
