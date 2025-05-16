<?php

namespace App\Nova\Credit\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class GrantGracePeriod extends Action
{
    use InteractsWithQueue, Queueable;


    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        if ($models->count() > 1) {
            return Action::danger(__('credit.select_one_account'));
        }

        $creditAccount = $models->first();

        try {
            // Update the account status to in_grace
            $creditAccount->status = 'in_grace';
            $creditAccount->grace_period_days = $fields->days;
            $creditAccount->save();

            // Log the action
            activity()
                ->performedOn($creditAccount)
                ->withProperties([
                    'days' => $fields->days,
                    'reason' => $fields->reason,
                ])
                ->log(__('credit.granted_grace_period'));

            return Action::message(__('credit.grace_period_granted', ['days' => $fields->days]));
        } catch (\Exception $e) {
            return Action::danger(__('credit.error_granting_grace', ['message' => $e->getMessage()]));
        }
    }

    /**
     * Get the fields available on the action.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            Number::make(__('credit.days'))
                ->required()
                ->min(1)
                ->max(90)
                ->help(__('credit.days_grant_grace')),

            Textarea::make(__('credit.reason'))
                ->required()
                ->help(__('credit.reason_grant_grace')),
        ];
    }
}
