<?php

declare(strict_types=1);

namespace Tests\SpearDevs\SyliusPushNotificationsPlugin\Integration\Repository\MySQLUserSubscriptionRepository;

use SpearDevs\SyliusPushNotificationsPlugin\Repository\MySQLUserSubscriptionRepository;
use Sylius\Bundle\ChannelBundle\Doctrine\ORM\ChannelRepository;
use Tests\SpearDevs\SyliusPushNotificationsPlugin\Integration\DataFixtures\ChannelFixture;
use Tests\SpearDevs\SyliusPushNotificationsPlugin\Integration\DataFixtures\CurrencyFixture;
use Tests\SpearDevs\SyliusPushNotificationsPlugin\Integration\DataFixtures\CustomerFixture;
use Tests\SpearDevs\SyliusPushNotificationsPlugin\Integration\DataFixtures\CustomerGroupFixture;
use Tests\SpearDevs\SyliusPushNotificationsPlugin\Integration\DataFixtures\LocaleFixture;
use Tests\SpearDevs\SyliusPushNotificationsPlugin\Integration\DataFixtures\RetailCustomerFixture;
use Tests\SpearDevs\SyliusPushNotificationsPlugin\Integration\DataFixtures\RetailShopUserFixture;
use Tests\SpearDevs\SyliusPushNotificationsPlugin\Integration\DataFixtures\RetailUserSubscriptionFixture;
use Tests\SpearDevs\SyliusPushNotificationsPlugin\Integration\DataFixtures\ShopUserFixture;
use Tests\SpearDevs\SyliusPushNotificationsPlugin\Integration\DataFixtures\UserSubscriptionFixture;
use Tests\SpearDevs\SyliusPushNotificationsPlugin\Integration\IntegrationTestCase;

final class MySQLUserSubscriptionRepositoryTest extends IntegrationTestCase
{
    private MySQLUserSubscriptionRepository $mySQLUserSubscriptionRepository;

    private ChannelRepository $channelRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mySQLUserSubscriptionRepository = self::getContainer()->get(MySQLUserSubscriptionRepository::class);
        $this->channelRepository = self::getContainer()->get(ChannelRepository::class);
    }

    public function testGetSubscriptionsForAllUsers(): void
    {
        // Given
        $this->loadFixtures(
            [
                new CustomerFixture(),
                new ShopUserFixture(),
                new LocaleFixture(),
                new CurrencyFixture(),
                new ChannelFixture(),
                new UserSubscriptionFixture(),
            ],
        );

        $channel = $this->channelRepository->findOneBy(['code' => ChannelFixture::CHANNEL_CODE]);

        //When
        $result = $this->mySQLUserSubscriptionRepository->getSubscriptionsForAllUsers($channel);

        //Then
        self::assertCount(1, iterator_to_array($result, false));
    }

    public function testGetSubscriptionsForUsersInGroup(): void
    {
        // Given
        $this->loadFixtures(
            [
                new CustomerGroupFixture(),
                new RetailCustomerFixture(),
                new RetailShopUserFixture(),
                new LocaleFixture(),
                new CurrencyFixture(),
                new ChannelFixture(),
                new RetailUserSubscriptionFixture(),
            ],
        );

        $channel = $this->channelRepository->findOneBy(['code' => ChannelFixture::CHANNEL_CODE]);

        //When
        $result = $this->mySQLUserSubscriptionRepository->getSubscriptionsForUsersInGroup(CustomerGroupFixture::RETAIL_CUSTOMER_GROUP_NAME, $channel);

        //Then
        self::assertCount(1, iterator_to_array($result, false));
    }

    public function testGetSubscriptionsForUserByEmail(): void
    {
        // Given
        $this->loadFixtures(
            [
                new CustomerFixture(),
                new ShopUserFixture(),
                new LocaleFixture(),
                new CurrencyFixture(),
                new ChannelFixture(),
                new UserSubscriptionFixture(),
            ],
        );

        $channel = $this->channelRepository->findOneBy(['code' => ChannelFixture::CHANNEL_CODE]);

        //When
        $result = $this->mySQLUserSubscriptionRepository->getSubscriptionsForUserByEmail(CustomerFixture::CUSTOMER_EMAIL, $channel);

        //Then
        self::assertCount(1, iterator_to_array($result, false));
    }
}
