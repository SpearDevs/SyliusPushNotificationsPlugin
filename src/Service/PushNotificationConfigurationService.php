<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\Service;

use SpearDevs\SyliusPushNotificationsPlugin\Entity\PushNotificationConfiguration\PushNotificationConfigurationInterface;
use SpearDevs\SyliusPushNotificationsPlugin\Repository\PushNotificationConfiguration\PushNotificationConfigurationRepository;

final class PushNotificationConfigurationService
{
    public function __construct(
        private PushNotificationConfigurationRepository $pushNotificationConfigurationRepository,
        private string $imagesUrl,
    ) {
    }

    public function getLinkToPushNotificationIcon(): ?string
    {
        $pushNotificationConfiguration = $this->pushNotificationConfigurationRepository->findOneBy([]);

        if (null === $pushNotificationConfiguration) {
            return null;
        }

        /** @var $pushNotificationConfiguration PushNotificationConfigurationInterface */
        return $this->imagesUrl . $pushNotificationConfiguration->getIconPath();
    }
}
