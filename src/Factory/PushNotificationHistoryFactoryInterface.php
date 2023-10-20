<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\Factory;

use BenTools\WebPushBundle\Model\Response\PushResponse;
use SpearDevs\SyliusPushNotificationsPlugin\Entity\PushNotificationHistory\PushNotificationHistoryInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

interface PushNotificationHistoryFactoryInterface extends FactoryInterface
{
    public function createNew(): PushNotificationHistoryInterface;

    public function createNewWithPushNotificationData(string $pushTitle, string $pushContent, PushResponse $pushResponse): PushNotificationHistoryInterface;
}
