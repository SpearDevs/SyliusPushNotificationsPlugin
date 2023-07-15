<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\Service;

use SpearDevs\SyliusPushNotificationsPlugin\Context\ChannelContextInterface;
use SpearDevs\SyliusPushNotificationsPlugin\Entity\PushNotificationConfiguration\PushNotificationConfigurationInterface;
use SpearDevs\SyliusPushNotificationsPlugin\Repository\PushNotificationConfiguration\PushNotificationConfigurationRepositoryInterface;

final class PushNotificationConfigurationService
{
    public function __construct(
        private PushNotificationConfigurationRepositoryInterface $pushNotificationConfigurationRepository,
        private ChannelContextInterface $channelContext,
        private string $appScheme,
        private string $imagesDirectory,
    ) {
    }

    public function getLinkToPushNotificationIcon(): ?string
    {
        $channel = $this->channelContext->getChannel();
        $pushNotificationConfiguration = $this->pushNotificationConfigurationRepository->findOneBy(['channel' => $channel->getId()]);

        if (null === $pushNotificationConfiguration) {
            return null;
        }

        $appHost = $channel->getHostname();

        /** @var PushNotificationConfigurationInterface $pushNotificationConfiguration */
        return $this->appScheme . '://' . $appHost . $this->imagesDirectory . $pushNotificationConfiguration->getIconPath();
    }
}
