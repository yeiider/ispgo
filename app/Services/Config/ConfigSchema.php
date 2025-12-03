<?php

namespace App\Services\Config;

use Illuminate\Support\Arr;

class ConfigSchema
{
    /**
     * Load the settings schema from config/settings.php
     */
    public static function all(): array
    {
        return config('settings');
    }

    /**
     * Get all sections with groups and fields.
     */
    public static function sections(): array
    {
        return self::all() ?? [];
    }

    /**
     * Flatten fields with computed full paths.
     * Returns an array keyed by path: [ path => fieldMeta ]
     */
    public static function fieldsIndex(): array
    {
        $index = [];
        foreach (self::sections() as $sectionKey => $section) {
            // Each section has a "setting" meta and multiple groups (like 'general', 'billing_cycle', ...)
            foreach ($section as $groupKey => $group) {
                if ($groupKey === 'setting') {
                    continue;
                }
                if (!is_array($group)) {
                    continue;
                }
                foreach ($group as $fieldKey => $field) {
                    if ($fieldKey === 'setting') {
                        continue;
                    }
                    if (!is_array($field)) {
                        continue;
                    }
                    $path = self::buildPath("{$sectionKey}/{$groupKey}", (string)$fieldKey);
                    $meta = $field;
                    $meta['key'] = $fieldKey;
                    $meta['group'] = $groupKey;
                    $meta['section'] = $sectionKey;
                    $meta['path'] = $path;
                    $meta['type'] = self::mapFieldType($field['field'] ?? 'text-field');
                    // Resolve select options if provided as class name
                    if (($meta['type'] === 'select') && isset($meta['options']) && is_string($meta['options'])) {
                        $meta['options'] = self::resolveOptions($meta['options']);
                    }
                    $index[$path] = $meta;
                }
            }
        }
        return $index;
    }

    /**
     * Helper to build a Magento-like config path.
     * Example: general/provider/name -> basePath.fieldKey
     */
    protected static function buildPath(?string $basePath, string $fieldKey): string
    {
        $parts = array_filter([$basePath, $fieldKey]);
        return implode('/', $parts);
    }

    protected static function mapFieldType(string $field): string
    {
        return match ($field) {
            'boolean-field' => 'boolean',
            'select-field' => 'select',
            'text-field', 'textarea-field', 'password-field', 'image-field' => 'string',
            default => 'string',
        };
    }

    protected static function resolveOptions(string $providerClass): array
    {
        // Try static getConfig
        if (method_exists($providerClass, 'getConfig')) {
            return $providerClass::getConfig();
        }
        // Fallback to instance method
        if (class_exists($providerClass)) {
            $instance = app($providerClass);
            if (method_exists($instance, 'getConfig')) {
                return $instance->getConfig();
            }
        }
        return [];
    }

    /**
     * Search fields by label/name/path.
     */
    public static function search(string $term): array
    {
        $term = mb_strtolower($term);
        $results = [];
        foreach (self::fieldsIndex() as $path => $field) {
            $label = mb_strtolower($field['label'] ?? '');
            $name = mb_strtolower($field['name'] ?? '');
            if (str_contains($path, $term) || str_contains($label, $term) || str_contains($name, $term)) {
                $results[$path] = $field;
            }
        }
        return $results;
    }
}
