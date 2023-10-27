<?php

declare(strict_types=1);

namespace Tests\SpearDevs\SyliusPushNotificationsPlugin\Integration\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use SpearDevs\SyliusPushNotificationsPlugin\Entity\PushNotificationConfiguration\PushNotificationConfiguration;
use Sylius\Component\Core\Model\Channel;

final class PushNotificationConfigurationFixture extends Fixture
{
    public const CONFIGURATION_REFERENCE = 'configuration';

    public function load(ObjectManager $manager): void
    {
        $pushNotificationConfiguration = new PushNotificationConfiguration();
        $pushNotificationConfiguration->setChannel(
            $this->getReference(ChannelFixture::CHANNEL_REFERENCE, Channel::class),
        );

        $manager->persist($pushNotificationConfiguration);
        $manager->flush();
    }
}
