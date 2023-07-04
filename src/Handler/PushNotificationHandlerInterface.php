<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\Handler;

use SpearDevs\SyliusPushNotificationsPlugin\WebPush\WebPushInterface;

interface PushNotificationHandlerInterface
{
    public function sendToGroup(WebPushInterface $webPush, ?string $receiver = null): void;

    public function sendToUser(WebPushInterface $webPush, ?string $receiver = null): void;
}
