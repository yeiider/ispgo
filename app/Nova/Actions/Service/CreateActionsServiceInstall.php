<?php

namespace App\Nova\Actions\Service;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Actions\ActionResponse;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class CreateActionsServiceInstall extends Action
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
        $actions = false;
        foreach ($models as $model) {
            $actions = $model->createInstallation($fields->technician, $fields->installation_date, $fields->note);
        }
        if ($models->count() > 1) {
            return ActionResponse::visit('/resources/installations/lens/installations');
        } else {
            return ActionResponse::visit('/resources/installations/' . $actions->id);
        }
    }

    /**
     * Get the fields available on the action.
     *
     * @param NovaRequest $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        // Obtener todos los usuarios con el rol de technician
        $technicians = User::technicians()->pluck('name', 'id');

        return [
            Select::make('Technician')
                ->options($technicians)
                ->displayUsingLabels()
                ->rules('required'),
            DateTime::make("Installation Date")->rules(['required', 'date']),
            TextArea::make('Note')
        ];
    }
}
