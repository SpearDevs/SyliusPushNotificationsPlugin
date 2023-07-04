<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\Handler;

interface PushNotificationHandlerInterface
{
    public function sendToGroup(string $pushTitle, string $pushContent, ?string $receiver = null): void;

    public function sendToUser(string $pushTitle, string $pushContent, ?string $receiver = null): void;
}
