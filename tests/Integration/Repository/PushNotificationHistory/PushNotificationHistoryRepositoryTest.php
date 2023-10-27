<?php

declare(strict_types=1);

namespace Tests\SpearDevs\SyliusPushNotificationsPlugin\Integration\Repository\PushNotificationHistory;

use SpearDevs\SyliusPushNotificationsPlugin\Entity\PushNotificationHistory\PushNotificationHistory;
use SpearDevs\SyliusPushNotificationsPlugin\Repository\PushNotificationHistory\PushNotificationHistoryRepository;
use Sylius\Bundle\ChannelBundle\Doctrine\ORM\ChannelRepository;
use Sylius\Bundle\CoreBundle\Doctrine\ORM\CustomerRepository;
use Tests\SpearDevs\SyliusPushNotificationsPlugin\Integration\DataFixtures\ChannelFixture;
use Tests\SpearDevs\SyliusPushNotificationsPlugin\Integration\DataFixtures\CurrencyFixture;
use Tests\SpearDevs\SyliusPushNotificationsPlugin\Integration\DataFixtures\CustomerFixture;
use Tests\SpearDevs\SyliusPushNotificationsPlugin\Integration\DataFixtures\LocaleFixture;
use Tests\SpearDevs\SyliusPushNotificationsPlugin\Integration\DataFixtures\PushNotificationHistoryFixture;
use Tests\SpearDevs\SyliusPushNotificationsPlugin\Integration\DataFixtures\PushNotificationReceivedHistoryFixture;
use Tests\SpearDevs\SyliusPushNotificationsPlugin\Integration\DataFixtures\ShopUserFixture;
use Tests\SpearDevs\SyliusPushNotificationsPlugin\Integration\DataFixtures\UserSubscriptionFixture;
use Tests\SpearDevs\SyliusPushNotificationsPlugin\Integration\IntegrationTestCase;

final class PushNotificationHistoryRepositoryTest extends IntegrationTestCase
{
    private PushNotificationHistoryRepository $pushNotificationHistoryRepository;

    private CustomerRepository $customerRepository;

    private ChannelRepository $channelRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->pushNotificationHistoryRepository = self::getContainer()->get(PushNotificationHistoryRepository::class);
        $this->customerRepository = self::getContainer()->get(CustomerRepository::class);
        $this->channelRepository = self::getContainer()->get(ChannelRepository::class);
    }

    public function testGetCountOfNotReceivedCustomerPushNotifications(): void
    {
        // Given
        $this->loadFixtures(
            [
                new CustomerFixture(),
                new ShopUserFixture(),
                new LocaleFixture(),
                new CurrencyFixture(),
                new ChannelFixture(),
                new PushNotificationHistoryFixture(),
                new PushNotificationReceivedHistoryFixture(),
            ],
        );

        $customer = $this->customerRepository->findOneBy(['email' => CustomerFixture::CUSTOMER_EMAIL]);
        $shopUser = $customer->getUser();
        $channel = $this->channelRepository->findOneBy(['code' => ChannelFixture::CHANNEL_CODE]);

        // Then
        $allPushNotificationHistories = $this->pushNotificationHistoryRepository->findAll();
        self::assertCount(2, $allPushNotificationHistories);

        // When
        $result = $this->pushNotificationHistoryRepository->getCountOfNotReceivedCustomerPushNotifications($shopUser, $channel);

        // Then
        self::assertSame(1, $result);
    }

    public function testItSavesPushNotificationHistory(): void
    {
        // Given
        $this->loadFixtures(
            [
                new CustomerFixture(),
                new ShopUserFixture(),
                new LocaleFixture(),
                new CurrencyFixture(),
                new ChannelFixture(),
                new PushNotificationHistoryFixture(),
                new UserSubscriptionFixture(),
            ],
        );

        $customer = $this->customerRepository->findOneBy(['email' => CustomerFixture::CUSTOMER_EMAIL]);
        $shopUser = $customer->getUser();
        $channel = $this->channelRepository->findOneBy(['code' => ChannelFixture::CHANNEL_CODE]);

        // Then
        $pushNotificationHistoryBeforeSave = $this->pushNotificationHistoryRepository->findAll();
        self::assertCount(1, $pushNotificationHistoryBeforeSave);

        $newPushNotificationHistory = new PushNotificationHistory();
        $newPushNotificationHistory->setTitle(PushNotificationHistoryFixture::PUSH_TITLE);
        $newPushNotificationHistory->setContent(PushNotificationHistoryFixture::PUSH_TITLE);
        $newPushNotificationHistory->setResponseStatusCode(PushNotificationHistoryFixture::PUSH_RESPONSE_STATUS_CODE);
        $newPushNotificationHistory->setChannel($channel);
        $newPushNotificationHistory->setUser($shopUser);

        // When
        $this->pushNotificationHistoryRepository->save($newPushNotificationHistory);

        // Then
        $pushNotificationHistoryAfterSave = $this->pushNotificationHistoryRepository->findAll();
        self::assertCount(2, $pushNotificationHistoryAfterSave);
    }
}
