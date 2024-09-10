<?php

namespace App\Nova;

use App\Models\User;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\MultiSelect;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Textarea;
use Illuminate\Http\Request;
use Laravel\Nova\Http\Requests\NovaRequest;

class Box extends Resource
{
    public static $model = \App\Models\Box::class;

    public static $title = 'name';

    public static $search = [
        'id', 'name'
    ];

    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),

            Text::make('Name')
                ->sortable()
                ->rules('required', 'max:255'),

            MultiSelect::make('Users')
                ->options($this->getUserOptions())
                ->rules('required'),

            HasMany::make('Daily Boxes', 'dailyBoxes', DailyBox::class)
                ->sortable(),
        ];
    }

    public static function label()
    {
        return 'Boxes';
    }

    public static function singularLabel()
    {
        return 'Box';
    }
    /**
     * Get the list of user options.
     *
     * @return array
     */
    protected function getUserOptions()
    {
        return \App\Models\User::all()->pluck('name', 'id')->toArray();
    }

    public function actions(NovaRequest $request): array
    {
        return [
            Action::redirect('Invoice Pos', '/admin/pos')->standalone(),
        ];
    }
}
