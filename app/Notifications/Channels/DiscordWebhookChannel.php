<?php

declare(strict_types=1);

namespace App\Notifications\Channels;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DiscordWebhookChannel
{
    public function send(object $notifiable, Notification $notification): void
    {
        /** @var string|null $webhookUrl */
        $webhookUrl = $notifiable->routeNotificationForDiscordWebhook();

        if (empty($webhookUrl)) {
            return;
        }

        /** @var array<string, mixed> $payload */
        $payload                     = $notification->toDiscordWebhook($notifiable);
        $payload['allowed_mentions'] = ['parse' => []];

        $response = Http::post($webhookUrl, $payload);

        if ($response->failed()) {
            Log::warning('Discord webhook notification failed', [
                'status'          => $response->status(),
                'notifiable_type' => $notifiable::class,
                'notifiable_id'   => $notifiable->getKey(),
            ]);
        }
    }
}
