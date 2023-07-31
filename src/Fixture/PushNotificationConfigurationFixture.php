<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\Fixture;

use SpearDevs\SyliusPushNotificationsPlugin\Entity\PushNotificationConfiguration\PushNotificationConfigurationInterface;
use SpearDevs\SyliusPushNotificationsPlugin\Repository\PushNotificationConfiguration\PushNotificationConfigurationRepositoryInterface;
use Sylius\Bundle\FixturesBundle\Fixture\AbstractFixture;
use Sylius\Bundle\FixturesBundle\Fixture\FixtureInterface;
use Sylius\Component\Channel\Repository\ChannelRepositoryInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

final class PushNotificationConfigurationFixture extends AbstractFixture implements FixtureInterface
{
    public function __construct(
        private FactoryInterface $pushNotificationConfigurationFactory,
        private PushNotificationConfigurationRepositoryInterface $pushNotificationConfigurationRepository,
        private ChannelRepositoryInterface $channelRepository,
    ) {
    }

    public function load(array $options): void
    {
        $channels = $this->channelRepository->findAll();

        foreach ($channels as $channel) {
            /** @var PushNotificationConfigurationInterface $pushNotificationConfiguration */
            $pushNotificationConfiguration = $this->pushNotificationConfigurationFactory->createNew();
            $pushNotificationConfiguration->setChannel($channel);

            $this->pushNotificationConfigurationRepository->save($pushNotificationConfiguration);
        }
    }

    public function getName(): string
    {
        return 'push_notification_configuration';
    }
}
