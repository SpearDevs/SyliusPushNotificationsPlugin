<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\Fixture;

use Sylius\Bundle\FixturesBundle\Fixture\AbstractFixture;
use Sylius\Bundle\FixturesBundle\Fixture\FixtureInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use SpearDevs\SyliusPushNotificationsPlugin\Entity\PushNotificationConfiguration\PushNotificationConfigurationInterface;
use SpearDevs\SyliusPushNotificationsPlugin\Repository\PushNotificationConfiguration\PushNotificationConfigurationRepository;

final class PushNotificationConfigurationFixture extends AbstractFixture implements FixtureInterface
{
    public function __construct(
        private FactoryInterface $pushNotificationConfigurationFactory,
        private PushNotificationConfigurationRepository $pushNotificationConfigurationRepository
    ) {
    }

    public function load(array $options): void
    {
        /** @var PushNotificationConfigurationInterface $pushNotificationConfiguration */
        $pushNotificationConfiguration = $this->pushNotificationConfigurationFactory->createNew();
        $pushNotificationConfiguration->setIconPath(null);

        $this->pushNotificationConfigurationRepository->save($pushNotificationConfiguration);
    }

    public function getName(): string
    {
        return 'push_notification_configuration';
    }
}
