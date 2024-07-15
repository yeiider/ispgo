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

    public function replaceVariables($content)
    {
        // Patrón regex para coincidir con {{ variable }}
        $pattern = '/{{\s*(.*?)\s*}}/';

        return preg_replace_callback($pattern, function ($matches) {
            $variableExpression = $matches[1];

            // Manejar casos específicos
            if (str_starts_with($variableExpression, 'trans')) {
                return $this->handleTrans($variableExpression);
            }
            if (str_starts_with($variableExpression, 'url')) {
                return $this->handleUrl($variableExpression);
            }
            if (str_starts_with($variableExpression, 'asset')) {
                return $this->handleAsset($variableExpression);
            }

            // Resolver variables dinámicas
            return $this->resolveVariable($variableExpression);
        }, $content);
    }

    protected function handleTrans($expression): \Illuminate\Foundation\Application|array|string|\Illuminate\Contracts\Translation\Translator
    {
        // Extraer los parámetros de la función trans y procesarlos
        preg_match('/trans\s*"([^"]+)"\s*(.*)/', $expression, $matches);
        $translationKey = $matches[1];
        $parameters = $this->parseParameters($matches[2]);
        return trans($translationKey, $parameters);
    }

    protected function handleUrl($expression): \Illuminate\Foundation\Application|string|\Illuminate\Contracts\Routing\UrlGenerator
    {
        // Extraer la URL y procesar
        preg_match('/url\(([^)]+)\)/', $expression, $matches);
        $urlPath = trim($matches[1], "'");
        return url($urlPath);
    }

    protected function handleAsset($expression): string
    {
        // Extraer el asset y procesar
        preg_match('/asset\(([^)]+)\)/', $expression, $matches);
        $assetPath = trim($matches[1], "'");
        return asset($assetPath);
    }

    protected function resolveVariable($expression): string
    {
        return $this->data[$this->template->entity]?->{$expression} ?? "{{ $expression }}";
    }


    protected function parseParameters($parameterString)
    {
        $parameters = [];
        preg_match_all('/(\w+)=([^,\s]+)/', $parameterString, $matches, PREG_SET_ORDER);
        foreach ($matches as $match) {
            $parameters[$match[1]] = trim($match[2], '"');
        }
        return $parameters;
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
            $variables[$key] = $this->resolveVariablePath(str_replace('$', '', $matches[2][$index]));
        }
        return $variables;
    }

    protected function includeTemplates($content)
    {
        // Modificar el patrón regex para coincidir con el formato actualizado
        preg_match_all('/{{\s*template\s+template_id=(\d+)\s*}}/', $content, $matches);

        // Iterar a través de las coincidencias y reemplazar las plantillas
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


