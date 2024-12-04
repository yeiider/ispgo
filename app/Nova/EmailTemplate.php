<?php

namespace App\Nova;

use App\Nova\Resource;
use Ispgo\Ckeditor\Ckeditor;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Http\Requests\NovaRequest;

class EmailTemplate extends Resource
{
    public static $model = \App\Models\EmailTemplate::class;

    public static $title = 'name';

    public static $search = [
        'id', 'name', 'subject'
    ];

    public function fields(NovaRequest $request)
    {
        return [
            ID::make('ID')->sortable(),
            Boolean::make('Is Active')->default(true)
                ->sortable(),
            Text::make('Name')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('Subject')
                ->sortable()
                ->rules('required', 'max:255'),

            Ckeditor::make('Body')
                ->rules('required'),

            Ckeditor::make('Styles'),

            Select::make('Entities Variables', 'entity')->options(
                [
                    "invoice" => "Invoice",
                    "customer" => "Customer",
                    "service" => "Service",
                ]
            ),

            Text::make('Test Email')
                ->sortable()
                ->rules('nullable', 'email'),

            Textarea::make('Description'),

        ];
    }
}
