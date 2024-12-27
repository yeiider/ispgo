<?php

namespace App\Services;

use App\Models\HtmlTemplate;

class HtmlTemplateEngine
{
    protected $template;
    protected $data;

    /**
     * @param HtmlTemplate $template
     * @param array $data  Data asociada a la plantilla (p.e. ['customer' => $customer])
     */
    public function __construct(HtmlTemplate $template, array $data = [])
    {
        $this->template = $template;
        $this->data = $data;
    }

    /**
     * Retorna el contenido procesado (solo el body)
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

        // 2. Reemplazar variables del tipo {{ variable }}
        $content = $this->replaceVariables($content);

        // 3. Procesar traducciones del tipo {{trans "xxx" foo=$model.bar}}
        $content = $this->processTranslations($content);

        return $content;
    }

    /**
     * Reemplaza variables en el contenido.
     * Las variables se definen en la forma {{ variable }} y pueden incluir
     * llamadas a helpers como trans, url, asset, etc.
     */
    public function replaceVariables($content)
    {
        $pattern = '/{{\s*(.*?)\s*}}/';

        return preg_replace_callback($pattern, function ($matches) {
            $variableExpression = $matches[1];

            // Manejo de expresiones especiales
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
     * Maneja llamadas tipo {{trans "key" foo="bar"}}
     */
    protected function handleTrans($expression)
    {
        preg_match('/trans\s*"([^"]+)"\s*(.*)/', $expression, $matches);
        $translationKey = $matches[1];
        $parameters = $this->parseParameters($matches[2]);

        return trans($translationKey, $parameters);
    }

    /**
     * Maneja llamadas tipo {{ url('ruta/loquesea') }}
     */
    protected function handleUrl($expression)
    {
        preg_match('/url\(([^)]+)\)/', $expression, $matches);
        $urlPath = trim($matches[1], "'");
        return url($urlPath);
    }

    /**
     * Maneja llamadas tipo {{ asset('css/style.css') }}
     */
    protected function handleAsset($expression)
    {
        preg_match('/asset\(([^)]+)\)/', $expression, $matches);
        $assetPath = trim($matches[1], "'");
        return asset($assetPath);
    }

    /**
     * Resuelve variables dinámicas, ej: {{ customer.full_name }}
     */
    protected function resolveVariable($expression): string
    {
        // Dividir la expresión completo en partes (ej: "service.customer.full_name")
        $pathParts = explode('.', $expression);

        // Recuperar la entidad principal desde $this->template->entity
        $rootEntity = $this->template->entity;

        // Pausar si el primer elemento no coincide con la entidad
        if ($pathParts[0] !== $rootEntity) {
            return "{{ $expression }}"; // No coincide y se devuelve como está
        }

        // Obtener el modelo base desde la entidad
        $model = $this->data[$rootEntity] ?? null;

        // Si no existe el modelo principal, devolvemos la expresión sin interpretar
        if (!$model) {
            return "{{ $expression }}";
        }

        // Navegamos por el resto de la ruta dinámica
        array_shift($pathParts); // Eliminar la entidad raíz ('service')

        foreach ($pathParts as $part) {
            // Verificar si la propiedad existe en el modelo actual
            if (is_object($model) && isset($model->{$part})) {
                $model = $model->{$part}; // Avanzar al siguiente nivel
            } else {
                return "{{ $expression }}"; // Devolver sin cambios si no se encuentra
            }
        }

        // Devolver el valor resultante (convertido a string si es necesario)
        return (string) $model;
    }

    /**
     * Parsea parámetros del tipo foo="bar" baz="qux"
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
     * Procesa traducciones del tipo {{trans "key" variable=$customer.name}}
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

    protected function parseVariables($variableString)
    {
        $variables = [];
        preg_match_all('/(\w+)=(\$[\w.]+)/', $variableString, $matches);
        foreach ($matches[1] as $index => $key) {
            // str_replace para quitar el "$"
            $variables[$key] = $this->resolveVariablePath(str_replace('$', '', $matches[2][$index]));
        }
        return $variables;
    }

    /**
     * Aquí podrías implementar la lógica de resolveVariablePath, si vas a permitir
     * llamadas más complejas tipo $customer.address.street.
     * Por simplicidad, la omitimos o retornamos algo fijo, etc.
     */
    protected function resolveVariablePath($path)
    {
        // Ejemplo simple:
        return data_get($this->data[$this->template->entity], $path) ?? '';
    }

    /**
     * Incluye sub-plantillas. Supongamos que la sintaxis es:
     * {{ template template_id=12 }}
     */
    protected function includeTemplates($content)
    {
        preg_match_all('/{{\s*template\s+template_id=(\d+)\s*}}/', $content, $matches);

        foreach ($matches[0] as $index => $match) {
            $templateId = $matches[1][$index];
            $includedTemplate = HtmlTemplate::find($templateId);
            if ($includedTemplate) {
                $includedContent = $this->processTemplate($includedTemplate->body);
                $content = str_replace($match, $includedContent, $content);
            }
        }

        return $content;
    }
}
