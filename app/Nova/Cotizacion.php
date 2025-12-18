<?php

namespace App\Nova;

use App\Models\Cotizacion as CotizacionModel;
use App\Nova\Actions\UpdateCotizacionEstado;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;

class Cotizacion extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<CotizacionModel>
     */
    public static string $model = CotizacionModel::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     */
    public static $title = 'telefono';

    /**
     * The columns that should be searched.
     */
    public static $search = [
        'id', 'nombre', 'apellido', 'email', 'telefono', 'ciudad', 'plan'
    ];

    public static function label()
    {
        return 'Cotizaciones';
    }

    public static function singularLabel()
    {
        return 'Cotización';
    }

    /**
     * Get the fields displayed by the resource.
     */
    public function fields(Request $request): array
    {
        return [
            ID::make()->sortable(),

            Text::make('Nombre')->rules('required','max:100')->sortable(),
            Text::make('Apellido')->rules('required','max:100')->sortable(),
            Text::make('Email')->rules('required','email','max:150')->sortable(),
            Text::make('Teléfono', 'telefono')->rules('required','max:50')->sortable(),
            Text::make('Dirección', 'direccion')->rules('required','max:255'),
            Text::make('Ciudad')->rules('required','max:120')->sortable(),
            Text::make('Plan')->rules('required','max:150')->sortable(),

            Select::make('Canal')
                ->options([
                    'web' => 'Web',
                    'whatsapp' => 'WhatsApp',
                ])->displayUsingLabels()->rules('required')->sortable(),

            Select::make('Estado')
                ->options([
                    'pendiente' => 'Pendiente',
                    'atendida' => 'Atendida',
                    'cancelada' => 'Cancelada',
                    'no_contactado' => 'No Contactado',
                    'completada' => 'Completada',
                ])->displayUsingLabels()->rules('required')->sortable()->default('pendiente'),

            Text::make('Notas')->hideFromIndex()->nullable(),

            DateTime::make('Creado', 'created_at')->onlyOnIndex()->filterable(),
            DateTime::make('Actualizado', 'updated_at')->onlyOnDetail(),
        ];
    }

    /**
     * Get the cards available for the request.
     */
    public function cards(Request $request): array
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     */
    public function filters(Request $request): array
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     */
    public function lenses(Request $request): array
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     */
    public function actions(Request $request): array
    {
        return [
            new UpdateCotizacionEstado,
        ];
    }
}
