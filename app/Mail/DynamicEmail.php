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
    protected $img_header;
    protected $mailConfig;

    public function __construct($data, EmailTemplate $template, string|null $img_header = null)
    {
        $this->data = $data;
        $this->template = $template;
        $this->img_header = $img_header;
    }

    public function build(): DynamicEmail
    {
        $engine = new \App\Services\EmailTemplateEngine($this->template, $this->data);
        $content = $engine->renderContentOnly(); // Renderiza solo el contenido
        $subject = $engine->replaceVariables($this->template->subject);

        return $this->view('emails.default')
            ->with([
                'content' => $content,
                'styles' => $this->template->styles,
                'img_header' => $this->img_header,
            ])
            ->subject($subject);
    }
}
