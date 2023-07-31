<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\Uploader;

use SpearDevs\SyliusPushNotificationsPlugin\Entity\PushNotificationConfiguration\PushNotificationConfigurationInterface;

interface PushNotificationIconUploaderInterface
{
    public function upload(PushNotificationConfigurationInterface $configuration): void;

    public function remove(string $path): bool;
}
