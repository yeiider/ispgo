<?php

namespace App\Nova\Actions\Invoice;

use App\Helpers\Utils;
use App\Models\EmailTemplate;
use App\Models\Invoice\Invoice;
use App\Settings\InvoiceProviderConfig;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Actions\ActionResponse;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;

class SendInvoiceNotification extends Action
{
    use InteractsWithQueue;
    use Queueable;
    public $withoutActionEvents = true;

    public function name(): string
    {
        return __('invoice.send_notification');
    }

    /**
     * Perform the action on the given models.
     *
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models): mixed
    {
        $templateId = InvoiceProviderConfig::emailTemplateCreatedInvoice();

        if (!$templateId) {
            return ActionResponse::danger(__('Email template not found!'));
        }

        $emailTemplate = EmailTemplate::where('id', $templateId)->first();
        $img_header = asset('/img/invoice/email-header.jpeg');

        $errors = []; // Array para almacenar los errores específicos de cada factura

        foreach ($models as $invoice) {
            /**
             * @var Invoice $invoice
             */
            try {
                Utils::sendInvoiceEmail($invoice, $emailTemplate, $img_header);
            } catch (\Exception $e) {
                // Maneja el error y almacena información para el mensaje de respuesta
                $errors[] = __('Error sending email for invoice #:id: :message', [
                    'id' => $invoice->id,
                    'message' => $e->getMessage(),
                ]);
            }
        }

        // Si hubo errores, los mostramos en el mensaje de respuesta
        if (!empty($errors)) {
            return ActionResponse::danger(__('Some emails failed to send! Details:') . ' ' . implode('; ', $errors));
        }

        return Action::message(__('All emails sent successfully!'));
    }

    /**
     * Get the fields available on the action.
     *
     * @return array<int, Field>
     */
    public function fields(NovaRequest $request): array
    {
        return [];
    }
}
