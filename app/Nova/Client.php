<?php

namespace App\Nova;

use Laravel\Nova\Resource;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Boolean;
use Illuminate\Http\Request;
use Laravel\Passport\Client as PassportClient;

class Client extends Resource
{
    /**
     * El modelo Eloquent al que hace referencia este Resource.
     *
     * @var string
     */
    public static $model = PassportClient::class;

    /**
     * El valor de la columna que se usará para mostrar este Resource.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * Indica qué columnas se pueden buscar con la barra de búsqueda global.
     *
     * @var array
     */
    public static $search = [
        'id', 'name', 'provider', 'redirect'
    ];

    /**
     * Retorna los campos que se mostrarán en la vista de detalle/creación/edición.
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),

            Text::make('Name')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('Secret')
                ->hideFromIndex()
                ->hideWhenCreating() // Normalmente la secret la genera Passport
                ->hideWhenUpdating()
                ->nullable(),

            // Si deseas mostrar el user_id, por si el client está asociado a un usuario
            // Text::make('User Id', 'user_id')
            //     ->nullable(),

            Text::make('Provider')
                ->withMeta(['value' => 'users'])  // Valor por defecto
                ->hideWhenUpdating()              // Solo se mostrará cuando se crea por primera vez
                ->nullable(),

            Text::make('Redirect')
                ->rules('required', 'url'), // Requerimos URL, typical en OAuth

            Boolean::make('Personal Access Client', 'personal_access_client'),
            Boolean::make('Password Client', 'password_client'),
            Boolean::make('Revoked'),
        ];
    }

    /**
     * Si deseas asignar el valor por defecto para provider directamente
     * al instanciar el modelo, puedes sobreescribir newModel():
     */
    public static function newModel()
    {
        $model = parent::newModel();
        if (!$model->secret) {
            $model->secret = \Illuminate\Support\Str::random(40);
        }
        $model->provider = 'users';
        return $model;
    }

    public static function label(): \Illuminate\Foundation\Application|array|string|\Illuminate\Contracts\Translation\Translator|null
    {
        return __('Oauth Clients');
    }
}
