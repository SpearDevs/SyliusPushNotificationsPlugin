<?php

declare(strict_types=1);

namespace Tests\SpearDevs\SyliusPushNotificationsPlugin\Integration\Repository\PushNotificationConfiguration;

use SpearDevs\SyliusPushNotificationsPlugin\Entity\PushNotificationConfiguration\PushNotificationConfiguration;
use SpearDevs\SyliusPushNotificationsPlugin\Repository\PushNotificationConfiguration\PushNotificationConfigurationRepository;
use SpearDevs\SyliusPushNotificationsPlugin\Repository\PushNotificationHistory\PushNotificationHistoryRepository;
use Sylius\Bundle\ChannelBundle\Doctrine\ORM\ChannelRepository;
use Sylius\Bundle\CoreBundle\Doctrine\ORM\CustomerRepository;
use Tests\SpearDevs\SyliusPushNotificationsPlugin\Integration\DataFixtures\ChannelFixture;
use Tests\SpearDevs\SyliusPushNotificationsPlugin\Integration\DataFixtures\CurrencyFixture;
use Tests\SpearDevs\SyliusPushNotificationsPlugin\Integration\DataFixtures\LocaleFixture;
use Tests\SpearDevs\SyliusPushNotificationsPlugin\Integration\IntegrationTestCase;

final class PushNotificationConfigurationRepositoryTest extends IntegrationTestCase
{
    private PushNotificationHistoryRepository $pushNotificationHistoryRepository;

    private CustomerRepository $customerRepository;

    private ChannelRepository $channelRepository;

    private PushNotificationConfigurationRepository $pushNotificationConfigurationRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->pushNotificationHistoryRepository = self::getContainer()->get(PushNotificationHistoryRepository::class);
        $this->customerRepository = self::getContainer()->get(CustomerRepository::class);
        $this->channelRepository = self::getContainer()->get(ChannelRepository::class);
        $this->pushNotificationConfigurationRepository = self::getContainer()->get(PushNotificationConfigurationRepository::class);
    }

    public function testItSavePushNotificationConfiguration(): void
    {
        // Given
        $expectedChannelCountAfterSave = 1;

        $this->loadFixtures(
            [
                new LocaleFixture(),
                new CurrencyFixture(),
                new ChannelFixture(),
            ],
        );

        $channel = $this->channelRepository->findOneBy(['code' => ChannelFixture::CHANNEL_CODE]);

        $resultBeforeSave = $this->pushNotificationConfigurationRepository->findAll();

        //Then
        self::assertEmpty($resultBeforeSave);

        //When
        $configuration = new PushNotificationConfiguration();
        $configuration->setChannel($channel);

        $this->pushNotificationConfigurationRepository->save($configuration);

        $resultAfterSave = $this->pushNotificationConfigurationRepository->findAll();

        //Then
        self::assertCount($expectedChannelCountAfterSave, $resultAfterSave);
    }
}
