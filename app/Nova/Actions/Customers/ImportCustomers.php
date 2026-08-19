<?php

namespace App\Nova\Actions\Customers;

use App\Services\Customers\CustomerImporterService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Fields\Select;

class ImportCustomers extends Action
{
    use InteractsWithQueue, Queueable;

    public $name = 'Importar Clientes (CSV)';

    public $standalone = true;

    public function fields(\Laravel\Nova\Http\Requests\NovaRequest $request): array
    {
        return [
            File::make('Archivo CSV', 'csv_file')
                ->acceptedTypes('.csv,text/csv')
                ->rules('required', 'file')
                ->help('Cargue un archivo CSV. Vea /samples/customers_import_example.csv para el formato.'),
            Select::make('Modo de importación', 'mode')
                ->options([
                    'create_only' => 'Crear solamente (si existe, omitir)',
                    'update_only' => 'Actualizar solamente (si no existe, omitir)',
                    'create_or_update' => 'Crear o actualizar (recomendado)'
                ])->rules('required')->displayUsingLabels()
        ];
    }

    public function handle(ActionFields $fields, Collection $models)
    {
        $file = $fields->csv_file;
        $mode = $fields->mode ?? 'create_or_update';

        if (!$file || !file_exists($file->getRealPath())) {
            return Action::danger('Archivo no válido.');
        }

        $importer = new CustomerImporterService();
        $result = $importer->importCsv($file->getRealPath(), $mode);

        if (!$result['success'] && empty($result['stats']['created']) && empty($result['stats']['updated'])) {
            return Action::danger('Error en la importación: ' . $result['message']);
        }

        return Action::message($result['message']);
    }
}
