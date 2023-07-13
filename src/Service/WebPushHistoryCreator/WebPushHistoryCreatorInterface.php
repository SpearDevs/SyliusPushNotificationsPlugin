<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\Service\WebPushHistoryCreator;

use BenTools\WebPushBundle\Model\Response\PushResponse;
use SpearDevs\SyliusPushNotificationsPlugin\WebPush\WebPushInterface;

interface WebPushHistoryCreatorInterface
{
    public function create(WebPushInterface $webPush, PushResponse $pushResponse): void;
}
