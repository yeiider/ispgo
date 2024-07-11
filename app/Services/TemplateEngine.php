<?php

namespace App\Services;

use App\Models\EmailTemplate;

class TemplateEngine
{
    protected $template;
    protected $data;

    public function __construct(EmailTemplate $template, array $data = [])
    {
        $this->template = $template;
        $this->data = $data;
    }

    public function renderContentOnly()
    {
        return $this->processTemplate($this->template->body);
    }

    protected function processTemplate($content)
    {
        // Incluir sub-plantillas primero
        $content = $this->includeTemplates($content);

        // Reemplazar variables
        $content = $this->replaceVariables($content);

        // Procesar traducciones
        $content = $this->processTranslations($content);

        return $content;
    }

    protected function replaceVariables($content)
    {
        foreach ($this->data as $key => $value) {
            $content = str_replace("{{ $key }}", $value, $content);
        }
        return $content;
    }

    protected function processTranslations($content)
    {
        preg_match_all('/{{trans\s"([^"]*)"\s([^}]*)}}/', $content, $matches);
        foreach ($matches[0] as $index => $match) {
            $translationKey = $matches[1][$index];
            $variables = $this->parseVariables($matches[2][$index]);
            $translation = trans($translationKey, $variables);
            $content = str_replace($match, $translation, $content);
        }
        return $content;
    }

    protected function parseVariables($variableString)
    {
        $variables = [];
        preg_match_all('/(\w+)=(\$[\w.]+)/', $variableString, $matches);
        foreach ($matches[1] as $index => $key) {
            $variables[$key] = $this->data[str_replace('$', '', $matches[2][$index])];
        }
        return $variables;
    }

    protected function includeTemplates($content)
    {
        // Procesar inclusiones de plantillas por template_id
        preg_match_all('/{{template\stemplate_id=(\d+)}}/', $content, $matches);
        foreach ($matches[0] as $index => $match) {
            $templateId = $matches[1][$index];
            $includedTemplate = EmailTemplate::find($templateId);
            if ($includedTemplate) {
                $includedContent = $this->processTemplate($includedTemplate->body);
                $content = str_replace($match, $includedContent, $content);
            }
        }

        return $content;
    }
}
