<?php

declare(strict_types=1);

namespace Tests\SpearDevs\SyliusPushNotificationsPlugin\Integration\Event\Admin;

use SpearDevs\SyliusPushNotificationsPlugin\Entity\PushNotificationConfiguration\PushNotificationConfiguration;
use SpearDevs\SyliusPushNotificationsPlugin\Event\Admin\PushNotificationConfigurationEventSubscriber;
use SpearDevs\SyliusPushNotificationsPlugin\Repository\PushNotificationConfiguration\PushNotificationConfigurationRepository;
use Sylius\Bundle\ChannelBundle\Doctrine\ORM\ChannelRepository;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Tests\SpearDevs\SyliusPushNotificationsPlugin\Integration\DataFixtures\ChannelFixture;
use Tests\SpearDevs\SyliusPushNotificationsPlugin\Integration\DataFixtures\CurrencyFixture;
use Tests\SpearDevs\SyliusPushNotificationsPlugin\Integration\DataFixtures\LocaleFixture;
use Tests\SpearDevs\SyliusPushNotificationsPlugin\Integration\DataFixtures\PushNotificationConfigurationFixture;
use Tests\SpearDevs\SyliusPushNotificationsPlugin\Integration\IntegrationTestCase;

final class PushNotificationConfigurationEventSubscriberTest extends IntegrationTestCase
{
    private PushNotificationConfigurationEventSubscriber $pushNotificationConfigurationEventSubscriber;

    private PushNotificationConfigurationRepository $pushNotificationConfigurationRepository;

    private ChannelRepository $channelRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->pushNotificationConfigurationEventSubscriber = self::getContainer()->get(PushNotificationConfigurationEventSubscriber::class);
        $this->pushNotificationConfigurationRepository = self::getContainer()->get(PushNotificationConfigurationRepository::class);
        $this->channelRepository = self::getContainer()->get(ChannelRepository::class);
    }

    public function testUploadWithoutFile(): void
    {
        // Given
        $this->loadFixtures(
            [
                new LocaleFixture(),
                new CurrencyFixture(),
                new ChannelFixture(),
                new PushNotificationConfigurationFixture(),
            ],
        );

        $channel = $this->channelRepository->findOneBy(['code' => ChannelFixture::CHANNEL_CODE]);
        /** @var PushNotificationConfiguration $configuration */
        $configuration = $this->pushNotificationConfigurationRepository->findOneBy(['channel' => $channel]);

        //When
        $this->pushNotificationConfigurationEventSubscriber->prePushNotificationConfigurationUpdate(
            new ResourceControllerEvent($configuration),
        );

        self::assertNull($configuration->getIconPath());
    }
}
