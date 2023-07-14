<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\Generator;

use SpearDevs\SyliusPushNotificationsPlugin\Entity\PushNotificationConfiguration\PushNotificationConfigurationInterface;

interface IconPathGeneratorInterface
{
    public function generate(PushNotificationConfigurationInterface $configuration): string;
}
