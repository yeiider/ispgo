<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\EmailTemplate;

class DynamicEmail extends Mailable
{
    use Queueable, SerializesModels;

    protected $data;
    protected $template;

    public function __construct($data, EmailTemplate $template)
    {
        $this->data = $data;
        $this->template = $template;
    }

    public function build()
    {
        $engine = new \App\Services\TemplateEngine($this->template, $this->data);
        $content = $engine->renderContentOnly(); // Renderiza solo el contenido
        // Procesar el asunto
        $subject = $engine->replaceVariables($this->template->subject);

        return $this->view('emails.default')
            ->with([
                'content' => $content,
                'styles' => $this->template->styles,
            ])
            ->subject($subject);
    }
}
