<?php
namespace Core;

/**
 * Sends notifications to Discord via webhook.
 * Reads the webhook URL from /travian/discord_webhook.url
 */
define("DISCORD_WEBHOOK_URL", @trim(@file_get_contents("/travian/discord_webhook.url")) ?: "");

class Notification
{
    /**
     * Send a markdown-like message to Discord. The $db and $pin parameters are
     * kept for compatibility with existing calls; they are not used for Discord.
     */
    public static function markdown(DB $db, $text, $pin = false)
    {
        $webhook = DISCORD_WEBHOOK_URL;
        if (empty($webhook)) {
            return; // No webhook configured
        }

        $payload = json_encode([
            'content' => (string)$text,
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        $context = stream_context_create([
            'http' => [
                'method'  => 'POST',
                'header'  => "Content-Type: application/json\r\n",
                'content' => $payload,
                'timeout' => 5,
            ],
        ]);

        @file_get_contents($webhook, false, $context);
    }
}