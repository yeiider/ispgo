<?php

namespace App\Services\Config;

use App\Models\CoreConfigData;
use Illuminate\Support\Arr;

class ConfigService
{
    /**
     * Get current values for the given paths at a scope.
     * Returns array of [ path => value ]
     */
    public function getValues(array $paths, int $scopeId = 0): array
    {
        if (empty($paths)) {
            return [];
        }
        $rows = CoreConfigData::query()
            ->whereIn('path', $paths)
            ->where('scope_id', $scopeId)
            ->get(['path', 'value']);

        $map = [];
        foreach ($rows as $row) {
            $map[$row->path] = $row->value;
        }
        return $map;
    }

    /**
     * Upsert multiple path/value pairs for a given scope.
     * Validates against schema types and options.
     * @param array $items [ [path=>string, value=>mixed], ... ]
     * @return array normalized items with saved values
     * @throws \InvalidArgumentException when validation fails
     */
    public function upsertValues(array $items, int $scopeId = 0): array
    {
        $index = ConfigSchema::fieldsIndex();
        $saved = [];
        foreach ($items as $item) {
            $path = $item['path'] ?? null;
            if (!$path) {
                throw new \InvalidArgumentException('Each item must have a path');
            }
            if (!isset($index[$path])) {
                throw new \InvalidArgumentException("Unknown config path: {$path}");
            }
            $meta = $index[$path];
            $value = $item['value'] ?? null;
            $value = $this->castAndValidate($value, $meta);

            // Upsert
            CoreConfigData::updateOrCreate(
                ['scope_id' => $scopeId, 'path' => $path],
                ['value' => is_bool($value) ? ($value ? '1' : '0') : (string)($value ?? '')]
            );

            $saved[] = [
                'path' => $path,
                'value' => $this->presentValue($value, $meta),
                'type' => $meta['type'] ?? 'string',
                'label' => $meta['label'] ?? null,
            ];
        }

        return $saved;
    }

    /**
     * Build full field list enriched with current values for a scope.
     */
    public function fieldsWithValues(int $scopeId = 0): array
    {
        $fields = ConfigSchema::fieldsIndex();
        $values = $this->getValues(array_keys($fields), $scopeId);
        $result = [];
        foreach ($fields as $path => $meta) {
            $raw = $values[$path] ?? ($meta['default'] ?? null);
            $val = $this->castForOutput($raw, $meta);
            $result[] = array_merge($meta, [
                'value' => $val,
            ]);
        }
        return $result;
    }

    protected function castAndValidate($value, array $meta)
    {
        $type = $meta['type'] ?? 'string';
        $required = (bool)($meta['required'] ?? false);

        if ($required && ($value === null || $value === '')) {
            $fieldName = ($meta['label'] ?? $meta['path'] ?? '');
            throw new \InvalidArgumentException('Field "' . $fieldName . '" is required');
        }

        switch ($type) {
            case 'boolean':
                if (is_string($value)) {
                    $v = strtolower(trim($value));
                    if (in_array($v, ['1','true','yes','on'], true)) {
                        $value = true;
                    } elseif (in_array($v, ['0','false','no','off',''], true)) {
                        $value = false;
                    } else {
                        $value = (bool)$value;
                    }
                } else {
                    $value = (bool)$value;
                }
                break;
            case 'integer':
                if ($value === null || $value === '') { $value = null; break; }
                if (!is_numeric($value)) {
                    throw new \InvalidArgumentException('Value must be integer');
                }
                $value = (int)$value;
                break;
            case 'select':
                $allowed = collect($meta['options'] ?? [])->pluck('value')->all();
                if (!in_array($value, $allowed, true)) {
                    throw new \InvalidArgumentException('Invalid option for select');
                }
                $value = (string)$value;
                break;
            case 'string':
            default:
                $value = $value === null ? null : (string)$value;
        }

        return $value;
    }

    protected function castForOutput($raw, array $meta)
    {
        $type = $meta['type'] ?? 'string';
        if ($raw === null) { return null; }
        switch ($type) {
            case 'boolean':
                return in_array((string)$raw, ['1','true','yes','on'], true);
            case 'integer':
                return (int)$raw;
            default:
                return $raw;
        }
    }

    protected function presentValue($value, array $meta)
    {
        return $value;
    }
}
