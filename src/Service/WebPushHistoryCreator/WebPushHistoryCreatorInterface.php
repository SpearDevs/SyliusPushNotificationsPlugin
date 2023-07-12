<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\Service\WebPushHistoryCreator;

use SpearDevs\SyliusPushNotificationsPlugin\WebPush\WebPushInterface;

interface WebPushHistoryCreatorInterface
{
    public function create(WebPushInterface $webPush, array $subscriptionsArray): void;
}
