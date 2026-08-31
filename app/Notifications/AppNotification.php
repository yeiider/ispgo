<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

/**
 * Notificación genérica in-app (canal database).
 *
 * Los datos se guardan en la tabla `notifications` y se exponen
 * al frontend vía GraphQL (query `notifications`).
 */
class AppNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $title,
        public string $message,
        public string $level = 'info', // success | info | warning | danger
        public ?string $actionUrl = null,
        public array $metadata = [],
    ) {}

    /**
     * Canales por los que se entrega la notificación.
     */
    public function via($notifiable): array
    {
        return ['database'];
    }

    /**
     * Datos que se persisten en la tabla notifications.
     *
     * @return array<string, mixed>
     */
    public function toDatabase($notifiable): array
    {
        return [
            'title'      => $this->title,
            'message'    => $this->message,
            'level'      => $this->level,
            'action_url' => $this->actionUrl,
            'metadata'   => $this->metadata,
        ];
    }

    /**
     * Representación para el array de la notificación.
     *
     * @return array<string, mixed>
     */
    public function toArray($notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}
