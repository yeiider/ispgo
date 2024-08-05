<?php

namespace App\Listeners;

use App\Events\CustomerCreated;
use App\Mail\DynamicEmail;
use App\Models\EmailTemplate;
use App\Settings\CustomerConfigProvider;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendWelcomeEmail
{
    public function handle(CustomerCreated $event): void
    {
        $customer = $event->customer;

        $sendWelcomeEmail = CustomerConfigProvider::getSendWelcomeEmail();

        if ($sendWelcomeEmail) {
            $templateId = CustomerConfigProvider::getSendWelcomeEmailTemplate();
            $template = EmailTemplate::find($templateId);

            if ($template) {
                $data = ['customer' => $customer];
                Mail::to($customer->email_address)->send(new DynamicEmail($data, $template));
            }
        }
    }
}
