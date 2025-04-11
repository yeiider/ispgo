<?php

namespace App\Nova\Actions\Invoice;

use App\Helpers\Utils;
use App\Models\Invoice\Invoice;
use App\Settings\GeneralProviderConfig;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
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

            $fileName = "invoice_{$invoice->increment_id}_{$invoice->status}.pdf";
            $filePath = "public/invoices/pdf/{$fileName}";
            $filePathOut = "/storage/invoices/pdf/{$fileName}";

            if (Storage::exists($filePath)) {
                return ActionResponse::download($fileName, url($filePathOut));
            }


            $companyName = GeneralProviderConfig::getCompanyName() ?? env('APP_NAME');
            $companyEmail = GeneralProviderConfig::getCompanyEmail() ?? env('MAIL_FROM_ADDRESS');
            $companyPhone = GeneralProviderConfig::getCompanyPhone() ?? null;

            $imgPath = public_path('img/invoice.svg');

            if (file_exists($imgPath)) {
                $imgContent = file_get_contents($imgPath);
                $img = base64_encode($imgContent);
                $img = 'data:image/svg+xml;base64,' . $img;
            } else {
                $img = '';
            }

            $options = ['locale' => 'es', 'currency' => 'COP'];
            $invoice->total = Utils::priceFormat($invoice->total, $options);
            $invoice->subtotal = Utils::priceFormat($invoice->subtotal, $options);
            $invoice->tax = Utils::priceFormat($invoice->tax, $options);

            $pdfContent = Pdf::loadView('invoices.preview', compact('invoice', 'companyName', 'companyEmail', 'companyPhone', 'img'));
            Storage::put($filePath, $pdfContent->output());

            $fileName = "invoice_{$invoice->increment_id}_{$invoice->status}.pdf";
            return ActionResponse::download($fileName, url($filePathOut));
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

    public function name()
    {
        return __('invoice.download_pdf');
    }
}
