<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\Repository\PushNotificationConfiguration;

use SpearDevs\SyliusPushNotificationsPlugin\Entity\PushNotificationConfiguration\PushNotificationConfigurationInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

interface PushNotificationConfigurationRepositoryInterface extends RepositoryInterface
{
    public function save(PushNotificationConfigurationInterface $pushNotificationHistory): void;
}
