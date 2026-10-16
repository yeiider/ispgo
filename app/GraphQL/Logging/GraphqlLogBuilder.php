<?php

namespace App\GraphQL\Logging;

use GraphQL\Language\AST\FieldNode;
use GraphQL\Language\AST\OperationDefinitionNode;
use GraphQL\Language\Parser;
use GraphQL\Utils\AST;
use Illuminate\Support\Str;

/**
 * Convierte una operación GraphQL (query + variables) en filas resumidas para graphql_logs:
 * una fila por cada campo raíz llamado (ej. `updateInvoice`, `customers`).
 */
class GraphqlLogBuilder
{
    private const MAX_SUMMARY_ARGS = 6;
    private const MAX_DETAIL_ARGS = 15;
    private const MAX_DETAILS_JSON = 4000;

    private const VERBS = [
        'create' => ['create', 'add', 'store', 'register', 'generate', 'new', 'import', 'upload', 'duplicate', 'clone'],
        'delete' => ['delete', 'remove', 'destroy', 'purge', 'force'],
        'update' => [
            'update', 'edit', 'set', 'change', 'toggle', 'mark', 'sync', 'upsert', 'save', 'assign', 'unassign',
            'approve', 'reject', 'cancel', 'close', 'open', 'activate', 'deactivate', 'suspend', 'enable',
            'disable', 'restore', 'move', 'transfer', 'apply', 'attach', 'detach', 'reset', 'rename', 'reorder',
        ],
    ];

    private const ACTION_VERB = [
        'view' => 'Consultó',
        'create' => 'Creó',
        'update' => 'Actualizó',
        'delete' => 'Eliminó',
        'other' => 'Ejecutó',
    ];

    /** Argumentos que no aportan al resumen. */
    private const NOISE_ARGS = ['first', 'sort_direction', 'sort_column', 'orderBy', 'per_page', 'perPage'];

    /**
     * @param  array{query?: string, variables?: array|null, operationName?: string|null}  $payload
     * @param  array  $changes  Cambios de modelos recogidos por GraphqlLogCollector
     * @return array<int, array<string, mixed>>
     */
    public function build(array $payload, array $changes): array
    {
        $query = $payload['query'] ?? null;
        if (!is_string($query) || trim($query) === '') {
            return [];
        }

        try {
            $document = Parser::parse($query, ['noLocation' => true]);
        } catch (\Throwable) {
            return [];
        }

        $operation = $this->selectOperation($document->definitions, $payload['operationName'] ?? null);
        if (!$operation) {
            return [];
        }

        $variables = is_array($payload['variables'] ?? null) ? $payload['variables'] : [];
        $operationType = $operation->operation; // query | mutation | subscription
        $ignored = config('graphql_logs.ignored_fields', []);
        $rows = [];
        $changesAttached = false;

        foreach ($operation->selectionSet->selections as $selection) {
            if (!$selection instanceof FieldNode) {
                continue;
            }

            $field = $selection->name->value;
            if (in_array($field, $ignored, true) || str_starts_with($field, '__')) {
                continue;
            }

            $args = [];
            foreach ($selection->arguments as $argument) {
                try {
                    $args[$argument->name->value] = AST::valueFromASTUntyped($argument->value, $variables);
                } catch (\Throwable) {
                    $args[$argument->name->value] = null;
                }
            }

            $fieldChanges = [];
            if ($operationType === 'mutation' && !$changesAttached) {
                $fieldChanges = $changes;
                $changesAttached = true;
            }

            $rows[] = $this->buildRow(
                $operationType,
                $operation->name?->value ?? ($payload['operationName'] ?? null),
                $field,
                $args,
                $fieldChanges
            );
        }

        return $rows;
    }

    private function selectOperation(iterable $definitions, ?string $operationName): ?OperationDefinitionNode
    {
        $first = null;
        foreach ($definitions as $definition) {
            if (!$definition instanceof OperationDefinitionNode) {
                continue;
            }
            if ($operationName && $definition->name?->value === $operationName) {
                return $definition;
            }
            $first ??= $definition;
        }

        return $first;
    }

    private function buildRow(string $operationType, ?string $operationName, string $field, array $args, array $changes): array
    {
        [$verb, $entityWords] = $this->splitVerb($field);
        $action = $this->resolveAction($operationType, $verb, $changes);

        if ($operationType === 'mutation' && $verb === null) {
            // Mutación sin verbo reconocible (ej. suspendService): la entidad sale de lo que cambió.
            $entityWords = '';
        }

        $entity = $entityWords !== ''
            ? Str::studly(Str::singular($entityWords))
            : ($changes[0]['model'] ?? null);

        $targetId = $this->extractId($args);
        $primary = $this->primaryChange($changes, $entity, $action, $targetId);

        if ($targetId === null && $primary) {
            $targetId = (string) $primary['id'];
        }
        $entity ??= $primary['model'] ?? null;

        $summary = $this->summarize($action, $operationType, $field, $entity, $targetId, $args, $primary, $changes);

        return [
            'operation_type' => $operationType,
            'operation_name' => $operationName ? Str::limit($operationName, 250, '') : null,
            'field' => Str::limit($field, 120, ''),
            'action' => $action,
            'entity' => $entity ? Str::limit($entity, 120, '') : null,
            'target_id' => $targetId !== null ? Str::limit($targetId, 64, '') : null,
            'summary' => Str::limit($summary, 495),
            'details' => $this->details($args, $changes),
        ];
    }

    /**
     * Separa el verbo inicial de un nombre camelCase: updateInvoice → ['update', 'Invoice'].
     */
    private function splitVerb(string $field): array
    {
        $words = preg_split('/(?<=[a-z0-9])(?=[A-Z])|_/', $field) ?: [$field];
        $first = strtolower($words[0]);

        foreach (self::VERBS as $verbs) {
            if (in_array($first, $verbs, true)) {
                return [$first, implode('', array_map('ucfirst', array_slice($words, 1)))];
            }
        }

        return [null, $field];
    }

    private function resolveAction(string $operationType, ?string $verb, array $changes): string
    {
        if ($operationType !== 'mutation') {
            return 'view';
        }

        if ($verb !== null) {
            foreach (self::VERBS as $action => $verbs) {
                if (in_array($verb, $verbs, true)) {
                    return $action;
                }
            }
        }

        // Sin verbo reconocible: deducir por lo que pasó en la base de datos.
        $events = array_unique(array_column($changes, 'event'));
        if ($events === ['deleted']) {
            return 'delete';
        }
        if ($events === ['created']) {
            return 'create';
        }

        return 'other';
    }

    private function extractId(array $args): ?string
    {
        foreach (['id', 'ids'] as $key) {
            if (array_key_exists($key, $args) && $args[$key] !== null) {
                return $this->idToString($args[$key]);
            }
        }

        foreach ($args as $value) {
            if (is_array($value) && isset($value['id']) && is_scalar($value['id'])) {
                return (string) $value['id'];
            }
        }

        return null;
    }

    private function idToString(mixed $value): ?string
    {
        if (is_array($value)) {
            $value = array_filter($value, 'is_scalar');
            return $value ? implode(',', array_slice($value, 0, 10)) . (count($value) > 10 ? '…' : '') : null;
        }

        return is_scalar($value) ? (string) $value : null;
    }

    /**
     * El cambio de modelo que mejor representa la acción (mismo modelo/id que la mutación).
     */
    private function primaryChange(array $changes, ?string $entity, string $action, ?string $targetId): ?array
    {
        if (!$changes) {
            return null;
        }

        $event = ['create' => 'created', 'update' => 'updated', 'delete' => 'deleted'][$action] ?? null;
        $best = null;
        $bestScore = -1;

        foreach ($changes as $change) {
            $score = 0;
            if ($entity && strcasecmp($change['model'], $entity) === 0) {
                $score += 2;
            }
            if ($targetId !== null && (string) $change['id'] === $targetId) {
                $score += 4;
            }
            if ($event && $change['event'] === $event) {
                $score += 1;
            }
            if ($score > $bestScore) {
                $best = $change;
                $bestScore = $score;
            }
        }

        return $best;
    }

    private function summarize(
        string $action,
        string $operationType,
        string $field,
        ?string $entity,
        ?string $targetId,
        array $args,
        ?array $primary,
        array $changes
    ): string {
        $verb = self::ACTION_VERB[$action];

        if ($action === 'view' && $targetId === null) {
            $text = "{$verb} {$field}";
        } elseif ($action === 'other') {
            $text = "{$verb} {$field}";
            if ($entity && $targetId !== null) {
                $text .= " sobre {$entity} #{$targetId}";
            }
        } else {
            if ($action === 'view') {
                $verb = 'Vio';
            }
            $text = trim("{$verb} " . ($entity ?? $field) . ($targetId !== null ? " #{$targetId}" : ''));
        }

        if ($primary && !empty($primary['label'])) {
            $text .= " ({$primary['label']})";
        }

        if ($primary && !empty($primary['fields'])) {
            $parts = [];
            foreach ($primary['fields'] as $key => $pair) {
                $parts[] = is_array($pair)
                    ? "{$key}: " . $this->display($pair[0]) . ' → ' . $this->display($pair[1])
                    : "{$key} {$pair}";
            }
            $text .= ': ' . implode(', ', $parts);
        } elseif (!in_array($action, ['create', 'delete'], true)) {
            $argText = $this->argsSummary($args, $targetId !== null);
            if ($argText !== '') {
                $text .= ($action === 'view' ? ' · filtros: ' : ' · ') . $argText;
            }
        }

        $others = count($changes) - ($primary ? 1 : 0);
        if ($others > 0) {
            $text .= " (+{$others} registro" . ($others > 1 ? 's' : '') . ' afectado' . ($others > 1 ? 's' : '') . ')';
        }

        return $text;
    }

    private function argsSummary(array $args, bool $skipId): string
    {
        $flat = $this->flatten($args);
        $parts = [];

        foreach ($flat as $key => $value) {
            if ($skipId && ($key === 'id' || $key === 'ids' || str_ends_with($key, '.id'))) {
                continue;
            }
            if ($key === 'page' && (int) $value <= 1) {
                continue;
            }
            $parts[] = "{$key}=" . $this->display(GraphqlLogCollector::shorten($value, 40));
            if (count($parts) >= self::MAX_SUMMARY_ARGS) {
                $remaining = count($flat) - self::MAX_SUMMARY_ARGS;
                if ($remaining > 0) {
                    $parts[] = "+{$remaining}";
                }
                break;
            }
        }

        return implode(', ', $parts);
    }

    /**
     * Aplana argumentos anidados a `clave.subclave => valor` omitiendo vacíos, ruido y datos sensibles.
     */
    private function flatten(array $args, string $prefix = '', int $depth = 0): array
    {
        $result = [];

        foreach ($args as $key => $value) {
            $name = $prefix === '' ? (string) $key : "{$prefix}.{$key}";

            if (in_array($key, self::NOISE_ARGS, true) || $value === null || $value === '' || $value === []) {
                continue;
            }
            if (GraphqlLogCollector::isHidden((string) $key)) {
                $result[$name] = '***';
                continue;
            }
            if (is_string($value) && (mb_strlen($value) > 500 || str_starts_with($value, 'data:'))) {
                $result[$name] = '[' . mb_strlen($value) . ' caracteres]';
                continue;
            }
            if (is_array($value)) {
                if (array_is_list($value)) {
                    $scalars = array_filter($value, 'is_scalar');
                    $result[$name] = count($scalars) === count($value) && count($value) <= 5
                        ? '[' . implode(',', $value) . ']'
                        : '[' . count($value) . ' elementos]';
                } elseif ($depth < 2) {
                    $result += $this->flatten($value, $name, $depth + 1);
                } else {
                    $result[$name] = '{…}';
                }
                continue;
            }
            if (is_object($value)) {
                $result[$name] = '[archivo]';
                continue;
            }

            $result[$name] = $value;
        }

        return $result;
    }

    private function details(array $args, array $changes): ?array
    {
        $flat = array_map(
            fn ($v) => GraphqlLogCollector::shorten($v),
            array_slice($this->flatten($args), 0, self::MAX_DETAIL_ARGS, true)
        );

        $details = array_filter(['args' => $flat, 'changes' => $changes]);
        if (!$details) {
            return null;
        }

        while (strlen(json_encode($details, JSON_UNESCAPED_UNICODE)) > self::MAX_DETAILS_JSON) {
            if (!empty($details['changes']) && count($details['changes']) > 1) {
                array_pop($details['changes']);
            } elseif (isset($details['args'])) {
                unset($details['args']);
            } else {
                unset($details['changes']);
            }
        }

        return $details ?: null;
    }

    private function display(mixed $value): string
    {
        return match (true) {
            $value === null => 'vacío',
            is_bool($value) => $value ? 'sí' : 'no',
            default => (string) $value,
        };
    }
}
