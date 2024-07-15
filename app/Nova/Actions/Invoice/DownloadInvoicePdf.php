<?php

namespace App\Nova\Actions;

use App\Models\Invoice\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Actions\ActionResponse;
use Laravel\Nova\Http\Requests\NovaRequest;

class DownloadInvoicePdf extends Action
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
        foreach ($models as $invoice) {
            /** @var Invoice $invoice */
            $pdf = PDF::loadView('invoices.pdf', ['invoice' => $invoice]);
            $pdfContent = $pdf->output();

            // Guardar el archivo PDF en el servidor (opcional)
            $fileName = 'invoice_' . $invoice->id . '.pdf';
            $path = storage_path('app/public/invoices/' . $fileName);
            file_put_contents($path, $pdfContent);

            // Proporcionar la URL de descarga al usuario
            $url = asset('storage/invoices/' . $fileName);

            return ActionResponse::download($fileName, $url);
        }

        return Action::message('No invoices were selected.');
    }

    /**
     * Get the fields available on the action.
     *
     * @param NovaRequest $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [];
    }
}
