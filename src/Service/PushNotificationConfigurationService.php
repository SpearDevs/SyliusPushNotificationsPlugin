<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\Service;

use SpearDevs\SyliusPushNotificationsPlugin\Entity\PushNotificationConfiguration\PushNotificationConfigurationInterface;
use SpearDevs\SyliusPushNotificationsPlugin\Repository\PushNotificationConfiguration\PushNotificationConfigurationRepositoryInterface;
use Psr\Container\ContainerInterface;

final class PushNotificationConfigurationService
{
    public function __construct(
        private PushNotificationConfigurationRepositoryInterface $pushNotificationConfigurationRepository,
        private ContainerInterface $container,
    ) {
    }

    public function getLinkToPushNotificationIcon(): ?string
    {
        $pushNotificationConfiguration = $this->pushNotificationConfigurationRepository->findOneBy([]);

        if (null === $pushNotificationConfiguration) {
            return null;
        }

        $appScheme = $this->container->getParameter('router.request_context.scheme');
        $appHost = $this->container->get('sylius.context.channel')->getChannel()->getHostName();
        $imagesDirectory = $this->container->getParameter('images_directory');

        /** @var $pushNotificationConfiguration PushNotificationConfigurationInterface */
        return $appScheme . '://' . $appHost . $imagesDirectory . $pushNotificationConfiguration->getIconPath();
    }
}
