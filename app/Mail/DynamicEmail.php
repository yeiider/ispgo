<?php

namespace App\Mail;

use App\Settings\EmailConfigProvider;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\EmailTemplate;

class DynamicEmail extends Mailable
{
    use Queueable, SerializesModels;

    protected $data;
    protected $template;
    protected $mailConfig;

    public function __construct($data, EmailTemplate $template, $mailConfig)
    {
        $this->data = $data;
        $this->template = $template;
        $this->mailConfig = $mailConfig;
    }

    public function build()
    {
        if (!app()->environment('local')) {
            config([
                'mail.mailers.smtp.host' => EmailConfigProvider::getHost(),
                'mail.mailers.smtp.port' => EmailConfigProvider::getPort(),
                'mail.mailers.smtp.username' => EmailConfigProvider::getUsername(),
                'mail.mailers.smtp.password' => EmailConfigProvider::getPassword(),
                'mail.mailers.smtp.encryption' => EmailConfigProvider::getSecurity(),
            ]);
        }

        $engine = new \App\Services\TemplateEngine($this->template, $this->data);
        $content = $engine->renderContentOnly(); // Renderiza solo el contenido
        $subject = $engine->replaceVariables($this->template->subject);

        return $this->view('emails.default')
            ->with([
                'content' => $content,
                'styles' => $this->template->styles,
            ])
            ->subject($subject);
    }
}
