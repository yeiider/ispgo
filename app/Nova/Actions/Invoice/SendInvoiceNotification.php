<?php

namespace App\Nova\Actions\Invoice;

use App\Mail\DynamicEmail;
use App\Models\EmailTemplate;
use App\Models\Invoice\Invoice;
use App\Settings\InvoiceProviderConfig;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Actions\ActionResponse;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Http\Requests\NovaRequest;

class SendInvoiceNotification extends Action
{
    use InteractsWithQueue;
    use Queueable;

    public function name(): string
    {
        return __('Send invoice by email');
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
        foreach ($models as $invoice) {
            /**
             * @var Invoice $invoice
             */
            //dd($invoice);

            Mail::to($invoice->email_address)->send(new DynamicEmail(['invoice' => $invoice], $emailTemplate, $img_header));
        }
        return Action::message('Email sent successfully!');
    }

    /**
     * Get the fields available on the action.
     *
     * @return array<int, \Laravel\Nova\Fields\Field>
     */
    public function fields(NovaRequest $request): array
    {
        return [];
    }
}
