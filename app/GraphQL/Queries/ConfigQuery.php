<?php

namespace App\GraphQL\Queries;

use App\Services\Config\ConfigSchema;
use App\Services\Config\ConfigService;
use App\Traits\HasSignedUrls;
use Illuminate\Support\Facades\Storage;

class ConfigQuery
{
    use HasSignedUrls;
    protected ConfigService $service;

    public function __construct()
    {
        $this->service = new ConfigService();
    }

    /**
     * Return full configuration sections -> groups -> fields (without values)
     */
    public function schema($_, array $args)
    {
        $sections = ConfigSchema::sections();
        $fieldsIndex = ConfigSchema::fieldsIndex();
        $result = [];
        foreach ($sections as $sectionKey => $section) {
            $groupsData = [];
            foreach ($section as $groupKey => $group) {
                if ($groupKey === 'setting' || !is_array($group)) {
                    continue;
                }
                $groupLabel = $group['setting']['label'] ?? ($group['label'] ?? null);
                $fields = [];
                foreach ($group as $fieldKey => $field) {
                    if ($fieldKey === 'setting' || !is_array($field)) {
                        continue;
                    }
                    $path = $sectionKey.'/'.$groupKey.'/'.$fieldKey;
                    $meta = $fieldsIndex[$path] ?? [];
                    $options = $meta['options'] ?? ($field['options'] ?? null);
                    if (is_array($options)) {
                        $options = array_map(function ($opt) {
                            return [
                                'label' => isset($opt['label']) ? (string)$opt['label'] : (string)($opt['value'] ?? ''),
                                'value' => isset($opt['value']) ? (string)$opt['value'] : (string)($opt['label'] ?? ''),
                            ];
                        }, $options);
                    }
                    $fields[] = [
                        'section' => $sectionKey,
                        'group' => $groupKey,
                        'key' => $fieldKey,
                        'label' => $field['label'] ?? ($meta['label'] ?? null),
                        'description' => $field['description'] ?? null,
                        'path' => $path,
                        'type' => $meta['type'] ?? 'string',
                        'required' => $meta['required'] ?? false,
                        'default' => isset($meta['default']) ? (string)$meta['default'] : (isset($field['default']) ? (string)$field['default'] : null),
                        'options' => $options,
                        'value' => null,
                    ];
                }
                $groupsData[] = [
                    'key' => $groupKey,
                    'label' => $groupLabel,
                    'path' => $sectionKey.'/'.$groupKey,
                    'fields' => $fields,
                ];
            }

            $sectionLabel = $section['setting']['label'] ?? ($section['label'] ?? null);
            $result[] = [
                'key' => $sectionKey,
                'label' => $sectionLabel,
                'path' => $sectionKey,
                'groups' => $groupsData,
            ];
        }
        return $result;
    }

    /**
     * Return flattened fields including current values for a scope
     */
    public function fields($_, array $args)
    {
        $scopeId = (int)($args['scope_id'] ?? 0);
        $list = $this->service->fieldsWithValues($scopeId);
        // Ensure value/default/options are string types where needed
        return array_map(function ($item) {
            // Si el campo es de tipo image-field, generar URL firmada
            if (($item['type'] ?? null) === 'image-field' && !empty($item['value'])) {
                $item['value'] = $this->generateSignedUrl($item['value']);
            }

            if (isset($item['value']) && $item['value'] !== null && !is_string($item['value'])) {
                $item['value'] = (string)$item['value'];
            }
            if (isset($item['default']) && $item['default'] !== null && !is_string($item['default'])) {
                $item['default'] = (string)$item['default'];
            }
            if (isset($item['options']) && is_array($item['options'])) {
                $item['options'] = array_map(function ($opt) {
                    return [
                        'label' => isset($opt['label']) ? (string)$opt['label'] : (string)($opt['value'] ?? ''),
                        'value' => isset($opt['value']) ? (string)$opt['value'] : (string)($opt['label'] ?? ''),
                    ];
                }, $item['options']);
            }
            return $item;
        }, $list);
    }

    /**
     * Get values for specific paths
     */
    public function values($_, array $args)
    {
        $paths = $args['paths'] ?? [];
        $scopeId = (int)($args['scope_id'] ?? 0);
        $index = ConfigSchema::fieldsIndex();
        $values = $this->service->getValues($paths, $scopeId);
        $result = [];
        foreach ($paths as $path) {
            $meta = $index[$path] ?? ['label' => null, 'type' => 'string'];
            $result[] = [
                'path' => $path,
                'value' => array_key_exists($path, $values) ? (string)$values[$path] : null,
                'type' => $meta['type'] ?? 'string',
                'label' => $meta['label'] ?? null,
            ];
        }
        return $result;
    }

    /**
     * Search fields by term and return enriched field list including current values
     */
    public function search($_, array $args)
    {
        $term = (string)$args['term'];
        $scopeId = (int)($args['scope_id'] ?? 0);
        $fields = ConfigSchema::search($term);
        $values = $this->service->getValues(array_keys($fields), $scopeId);
        $result = [];
        foreach ($fields as $path => $meta) {
            $result[] = array_merge($meta, [
                'value' => array_key_exists($path, $values)
                    ? (string)$values[$path]
                    : (isset($meta['default']) ? (string)$meta['default'] : null),
            ]);
        }
        return array_values($result);
    }
}
