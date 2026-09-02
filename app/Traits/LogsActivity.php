<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

trait LogsActivity
{
    protected static function bootLogsActivity()
    {
        static::created(function ($model) {
            $user = Auth::user();
            $userName = $user ? $user->name : 'Sistema';
            $module = $model->getActivityModule();
            $label = $model->getActivitySubjectLabel();

            ActivityLog::log(
                action: 'created',
                module: $module,
                description: "{$userName} creó {$label}",
                subject: $model,
                properties: ['attributes' => $model->getAttributes()],
                user: $user
            );
        });

        static::updated(function ($model) {
            $user = Auth::user();
            $userName = $user ? $user->name : 'Sistema';
            $module = $model->getActivityModule();
            $label = $model->getActivitySubjectLabel();

            $changes = $model->getChanges();
            // Evitar auditar cambios solo de timestamp si lo deseas, pero registramos si hay cambios
            if (!empty($changes)) {
                ActivityLog::log(
                    action: 'updated',
                    module: $module,
                    description: "{$userName} actualizó {$label}",
                    subject: $model,
                    properties: [
                        'old' => array_intersect_key($model->getOriginal(), $changes),
                        'attributes' => $changes,
                    ],
                    user: $user
                );
            }
        });

        static::deleted(function ($model) {
            $user = Auth::user();
            $userName = $user ? $user->name : 'Sistema';
            $module = $model->getActivityModule();
            $label = $model->getActivitySubjectLabel();

            ActivityLog::log(
                action: 'deleted',
                module: $module,
                description: "{$userName} eliminó {$label}",
                subject: $model,
                properties: ['attributes' => $model->getAttributes()],
                user: $user
            );
        });
    }

    /**
     * Módulo por defecto derivado de la tabla o sobreescribible en el modelo
     */
    public function getActivityModule(): string
    {
        if (property_exists($this, 'activityModule')) {
            return $this->activityModule;
        }

        return strtolower(class_basename($this));
    }

    /**
     * Etiqueta descriptiva para el sujeto
     */
    public function getActivitySubjectLabel(): string
    {
        if (property_exists($this, 'activitySubjectLabelField') && isset($this->{$this->activitySubjectLabelField})) {
            return strtolower(class_basename($this)) . " #" . $this->getKey() . " (" . $this->{$this->activitySubjectLabelField} . ")";
        }

        if (isset($this->name)) {
            return strtolower(class_basename($this)) . " '{$this->name}' (#{$this->getKey()})";
        }

        if (isset($this->title)) {
            return strtolower(class_basename($this)) . " '{$this->title}' (#{$this->getKey()})";
        }

        return strtolower(class_basename($this)) . " #" . $this->getKey();
    }
}
