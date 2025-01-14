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
            Boolean::make(__('Is Active'), 'is_active')->default(true)
                ->sortable(),
            Text::make(__('Name'), 'name')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make(__('Subject'), 'subject')
                ->sortable()
                ->rules('required', 'max:255'),

            Ckeditor::make(__('Body'), 'body')
                ->rules('required'),

            Ckeditor::make(__('Styles'), 'styles'),

            Select::make(__('Entities Variables'), 'entity')->options(
                [
                    "invoice" => __("Invoice"),
                    "customer" => __("Customer"),
                    "service" => __("Service"),
                ]
            )->displayUsingLabels(),

            Text::make(__('Test Email'), 'test_email')
                ->sortable()
                ->rules('nullable', 'email'),

            Textarea::make(__('Description'), 'description'),

        ];
    }

    public static function label(): \Illuminate\Foundation\Application|array|string|\Illuminate\Contracts\Translation\Translator|null
    {
        return __('Email Templates');
    }
}
