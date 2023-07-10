<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\WebPushSender;

use SpearDevs\SyliusPushNotificationsPlugin\WebPush\WebPushInterface;
use Sylius\Component\Core\Model\OrderInterface;

interface WebPushSenderInterface
{
    public function sendToGroup(WebPushInterface $webPush, ?string $receiver = null): void;

    public function sendToUser(WebPushInterface $webPush, ?string $receiver = null): void;

    public function sendOrderWebPush(OrderInterface $order, string $pushNotificationCode): void;
}
