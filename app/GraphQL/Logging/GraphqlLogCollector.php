<?php

namespace App\GraphQL\Logging;

use Illuminate\Database\Eloquent\Model;

/**
 * Acumula, durante una petición GraphQL, los modelos que se crearon,
 * actualizaron o eliminaron, para adjuntar al log un resumen de qué cambió.
 */
class GraphqlLogCollector
{
    private const MAX_MODELS = 10;
    private const MAX_FIELDS = 12;
    private const MAX_VALUE_LENGTH = 80;

    private const HIDDEN_FIELDS = [
        'password', 'remember_token', 'token', 'secret', 'api_key', 'access_token', 'refresh_token',
    ];

    private const IGNORED_FIELDS = ['created_at', 'updated_at'];

    private bool $active = false;

    /** @var array<int, array{event: string, model: string, id: mixed, label: ?string, fields?: array}> */
    private array $changes = [];

    public function start(): void
    {
        $this->active = true;
        $this->changes = [];
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function changes(): array
    {
        return $this->changes;
    }

    public function record(string $event, Model $model): void
    {
        if (!$this->active || count($this->changes) >= self::MAX_MODELS) {
            return;
        }

        foreach (config('graphql_logs.ignored_models', []) as $ignored) {
            if ($model instanceof $ignored) {
                return;
            }
        }

        $entry = [
            'event' => $event,
            'model' => class_basename($model),
            'id' => $model->getKey(),
            'label' => self::labelFor($model),
        ];

        if ($event === 'updated') {
            $fields = [];
            foreach ($model->getChanges() as $key => $new) {
                if (in_array($key, self::IGNORED_FIELDS, true)) {
                    continue;
                }
                if (count($fields) >= self::MAX_FIELDS) {
                    $fields['…'] = '+' . (count($model->getChanges()) - self::MAX_FIELDS) . ' campos';
                    break;
                }
                $hidden = self::isHidden($key);
                $fields[$key] = [
                    $hidden ? '***' : self::shorten($model->getOriginal($key)),
                    $hidden ? '***' : self::shorten($new),
                ];
            }

            if (empty($fields)) {
                return;
            }
            $entry['fields'] = $fields;
        }

        $this->changes[] = $entry;
    }

    public static function labelFor(Model $model): ?string
    {
        foreach (['name', 'title', 'full_name', 'customer_name', 'increment_id', 'code', 'subject', 'email'] as $attr) {
            $value = $model->getAttributes()[$attr] ?? null;
            if (is_scalar($value) && $value !== '') {
                return self::shorten($value, 60);
            }
        }

        $first = $model->getAttributes()['first_name'] ?? null;
        $last = $model->getAttributes()['last_name'] ?? null;
        if ($first || $last) {
            return self::shorten(trim("{$first} {$last}"), 60);
        }

        return null;
    }

    public static function isHidden(string $key): bool
    {
        $key = strtolower($key);
        foreach (self::HIDDEN_FIELDS as $hidden) {
            if (str_contains($key, $hidden)) {
                return true;
            }
        }

        return false;
    }

    public static function shorten(mixed $value, int $max = self::MAX_VALUE_LENGTH): mixed
    {
        if ($value instanceof \DateTimeInterface) {
            return $value->format('Y-m-d H:i:s');
        }
        if (is_array($value) || is_object($value)) {
            $value = json_encode($value, JSON_UNESCAPED_UNICODE);
        }
        if (is_string($value) && mb_strlen($value) > $max) {
            return mb_substr($value, 0, $max) . '…';
        }

        return $value;
    }
}
