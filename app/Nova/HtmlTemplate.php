<?php

namespace App\Nova;

use App\Models\HtmlTemplate as HtmlTemplateModel;
use Ispgo\Ckeditor\Ckeditor;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Resource;

class HtmlTemplate extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = HtmlTemplateModel::class;

    /**
     * The single value that should be used to represent the resource
     * when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @return array
     */
    public static $search = [
        'id', 'name', 'entity',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param NovaRequest $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),

            Text::make('Name', 'name')
                ->rules('required', 'max:255')
                ->sortable(),

            Text::make('Entity', 'entity')
                ->nullable()
                ->help('Opcional: indica el modelo al que está asociada la plantilla (ej: "customer", "invoice", etc.)'),

            Ckeditor::make('Body', 'body')
                ->rules('required')
                ->help('Inserta aquí el contenido HTML de la plantilla.'),
            Code::make('Styles', 'styles')
                ->language("css")
                ->help('Inserte aqui si quiere algun estilo custom para la plantilla.'),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param NovaRequest $request
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param NovaRequest $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param NovaRequest $request
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param NovaRequest $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [];
    }
}
