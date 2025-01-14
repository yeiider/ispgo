<?php

namespace App\Services;

abstract class BaseTemplateEngine
{
    /**
     * El modelo de plantilla (puede ser EmailTemplate o HtmlTemplate)
     *
     * @var mixed
     */
    protected $template;

    /**
     * Data asociada a la plantilla
     *
     * @var array
     */
    protected $data;

    /**
     * Constructor base para cualquier TemplateEngine
     *
     * @param mixed $template  Instancia de EmailTemplate o HtmlTemplate
     * @param array $data      Datos asociados a la plantilla
     */
    public function __construct($template, array $data = [])
    {
        $this->template = $template;
        $this->data = $data;
    }

    /**
     * Método público para devolver el contenido procesado.
     * Este método no debería cambiar entre EmailTemplate y HtmlTemplate.
     */
    public function renderContentOnly()
    {
        return $this->processTemplate($this->template->body);
    }

    /**
     * Procesa la plantilla: incluye sub-plantillas, reemplaza variables, etc.
     */
    protected function processTemplate($content)
    {
        // 1. Incluir sub-plantillas
        $content = $this->includeTemplates($content);

        // 2. Reemplazar variables {{ ... }}
        $content = $this->replaceVariables($content);

        // 3. Procesar traducciones {{trans "..." foo=$model.bar}}
        $content = $this->processTranslations($content);

        return $content;
    }

    /**
     * Reemplaza variables en el contenido.
     * Maneja {{trans "..."}} | {{url()}} | {{asset()}} | etc.
     */
    public function replaceVariables($content)
    {
        $pattern = '/{{\s*(.*?)\s*}}/';

        return preg_replace_callback($pattern, function ($matches) {
            $variableExpression = $matches[1];

            if (str_starts_with($variableExpression, 'trans')) {
                return $this->handleTrans($variableExpression);
            }

            if (str_starts_with($variableExpression, 'url')) {
                return $this->handleUrl($variableExpression);
            }

            if (str_starts_with($variableExpression, 'asset')) {
                return $this->handleAsset($variableExpression);
            }

            return $this->resolveVariable($variableExpression);
        }, $content);
    }

    /**
     * Maneja {{trans "key" foo="bar"}}
     */
    protected function handleTrans($expression)
    {
        preg_match('/trans\s*"([^"]+)"\s*(.*)/', $expression, $matches);
        $translationKey = $matches[1];
        $parameters = $this->parseParameters($matches[2]);

        return trans($translationKey, $parameters);
    }

    /**
     * Maneja {{ url('ruta') }}
     */
    protected function handleUrl($expression)
    {
        preg_match('/url\(([^)]+)\)/', $expression, $matches);
        $urlPath = trim($matches[1], "'");
        return url($urlPath);
    }

    /**
     * Maneja {{ asset('ruta/al/asset.css') }}
     */
    protected function handleAsset($expression)
    {
        preg_match('/asset\(([^)]+)\)/', $expression, $matches);
        $assetPath = trim($matches[1], "'");
        return asset($assetPath);
    }

    /**
     * Resuelve variables dinámicas (p.e. {{customer.full_name}})
     *
     * Este método podría ser distinto si tus modelos EmailTemplate y HtmlTemplate
     * trabajan con diferentes “entity”.
     *
     * Por eso, podrías definirlo como `abstract` y que cada hijo lo implemente a su manera,
     * o si realmente son iguales, se comparte aquí.
     */
    protected function resolveVariable($expression): string
    {
        // Ejemplo:
        $pathParts = explode('.', $expression);
        $rootEntity = $this->template->entity;

        if ($pathParts[0] !== $rootEntity) {
            return "{{ $expression }}";
        }

        $model = $this->data[$rootEntity] ?? null;
        if (!$model) {
            return "{{ $expression }}";
        }

        array_shift($pathParts); // Quitar la parte 'rootEntity'
        foreach ($pathParts as $part) {
            if (is_object($model) && isset($model->{$part})) {
                $model = $model->{$part};
            } else {
                return "{{ $expression }}";
            }
        }
        return (string) $model;
    }


    /**
     * Procesa llamadas {{trans "key" variable=$entity.something}}
     */
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


    /**
     * Para parsear parámetros del tipo foo="bar" baz="qux"
     */
    protected function parseParameters($parameterString)
    {
        $parameters = [];
        preg_match_all('/(\w+)=([^,\s]+)/', $parameterString, $matches, PREG_SET_ORDER);
        foreach ($matches as $match) {
            $parameters[$match[1]] = trim($match[2], '"');
        }
        return $parameters;
    }


    /**
     * Para parsear variables en la forma variable=$customer.name
     */
    protected function parseVariables($variableString)
    {
        $variables = [];
        preg_match_all('/(\w+)=(\$[\w.]+)/', $variableString, $matches);
        foreach ($matches[1] as $index => $key) {
            $variables[$key] = $this->resolveVariablePath(
                str_replace('$', '', $matches[2][$index])
            );
        }
        return $variables;
    }


    /**
     * Este método es genérico para resolver caminos $customer.address.street
     * usando data_get() de Laravel, p. ej.
     *
     * Lo llamamos desde parseVariables(...).
     */
    protected function resolveVariablePath($path)
    {
        // Ejemplo:
        return data_get($this->data[$this->template->entity] ?? null, $path, '');
    }


    /**
     * Incluye subplantillas con sintaxis {{ template template_id=12 }}
     *
     * Ojo: en tu caso esto depende de si usas EmailTemplate o HtmlTemplate.
     * Podrías hacer un método abstracto `findTemplateById($id)` y que cada subclase
     * lo implemente con su propio modelo.
     */
    protected function includeTemplates($content)
    {
        preg_match_all('/{{\s*template\s+template_id=(\d+)\s*}}/', $content, $matches);

        foreach ($matches[0] as $index => $match) {
            $templateId = $matches[1][$index];

            // --- Llamamos a un método abstracto
            $includedTemplate = $this->findTemplateById($templateId);

            if ($includedTemplate) {
                $includedContent = $this->processTemplate($includedTemplate->body);
                $content = str_replace($match, $includedContent, $content);
            }
        }

        return $content;
    }

    /**
     * Método abstracto para buscar subplantillas según su ID,
     * ya que EmailTemplate y HtmlTemplate están en tablas distintas.
     */
    abstract protected function findTemplateById($id);
}
