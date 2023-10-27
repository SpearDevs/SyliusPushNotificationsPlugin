<?php

declare(strict_types=1);

namespace Tests\SpearDevs\SyliusPushNotificationsPlugin\Integration\Twig\Extension;

use SpearDevs\SyliusPushNotificationsPlugin\Repository\PushNotificationHistory\PushNotificationHistoryRepository;
use SpearDevs\SyliusPushNotificationsPlugin\Twig\Extension\PushNotificationHistoryTwigExtension;
use Sylius\Bundle\CoreBundle\Doctrine\ORM\CustomerRepository;
use Tests\SpearDevs\SyliusPushNotificationsPlugin\Integration\DataFixtures\ChannelFixture;
use Tests\SpearDevs\SyliusPushNotificationsPlugin\Integration\DataFixtures\CurrencyFixture;
use Tests\SpearDevs\SyliusPushNotificationsPlugin\Integration\DataFixtures\CustomerFixture;
use Tests\SpearDevs\SyliusPushNotificationsPlugin\Integration\DataFixtures\LocaleFixture;
use Tests\SpearDevs\SyliusPushNotificationsPlugin\Integration\DataFixtures\PushNotificationHistoryFixture;
use Tests\SpearDevs\SyliusPushNotificationsPlugin\Integration\DataFixtures\PushNotificationReceivedHistoryFixture;
use Tests\SpearDevs\SyliusPushNotificationsPlugin\Integration\DataFixtures\ShopUserFixture;
use Tests\SpearDevs\SyliusPushNotificationsPlugin\Integration\IntegrationTestCase;

final class PushNotificationHistoryTwigExtensionTest extends IntegrationTestCase
{
    private PushNotificationHistoryRepository $pushNotificationHistoryRepository;

    private CustomerRepository $customerRepository;

    private PushNotificationHistoryTwigExtension $pushNotificationHistoryTwigExtension;

    protected function setUp(): void
    {
        parent::setUp();

        $this->pushNotificationHistoryRepository = self::getContainer()->get(PushNotificationHistoryRepository::class);
        $this->customerRepository = self::getContainer()->get(CustomerRepository::class);
        $this->pushNotificationHistoryTwigExtension = self::getContainer()->get(PushNotificationHistoryTwigExtension::class);
    }

    public function testGetCountOfNotReceivedPushNotifications(): void
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

        // Then
        $allPushNotificationHistories = $this->pushNotificationHistoryRepository->findAll();
        self::assertCount(2, $allPushNotificationHistories);

        // When
        $result = $this->pushNotificationHistoryTwigExtension->getCountOfNotReceivedPushNotifications($shopUser);

        // Then
        self::assertSame(1, $result);
    }
}
