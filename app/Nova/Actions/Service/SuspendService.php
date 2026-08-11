<?php

namespace App\Nova\Actions\Service;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Http\Requests\NovaRequest;

class SuspendService extends Action
{
    use InteractsWithQueue, Queueable;

    public $withoutActionEvents = true;

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
            $model->suspend();
        }
        return Action::message(__('Services successfully suspended'));
    }

    /**
     * Get the fields available on the action.
     *
     * @param NovaRequest $request
     * @return array
     */
    public function fields(NovaRequest $request): array
    {
        return [];
    }

    public function name(): \Illuminate\Foundation\Application|\Stringable|array|string|\Illuminate\Contracts\Translation\Translator|null
    {
        return __('service.actions.suspend');
    }
}
