<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\Handler;

interface PushNotificationHandlerInterface
{
    public function send(array $subscriptions, string $pushTitle, string $pushContent): void;
}
