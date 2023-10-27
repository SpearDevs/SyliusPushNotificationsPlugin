<?php

declare(strict_types=1);

namespace Tests\SpearDevs\SyliusPushNotificationsPlugin\Integration\Manager;

use BenTools\WebPushBundle\Model\Subscription\UserSubscriptionInterface;
use SpearDevs\SyliusPushNotificationsPlugin\Manager\UserSubscriptionManager;
use SpearDevs\SyliusPushNotificationsPlugin\Repository\MySQLUserSubscriptionRepository;
use Sylius\Bundle\CoreBundle\Doctrine\ORM\CustomerRepository;
use Sylius\Component\Core\Model\ShopUser;
use Tests\SpearDevs\SyliusPushNotificationsPlugin\Integration\DataFixtures\ChannelFixture;
use Tests\SpearDevs\SyliusPushNotificationsPlugin\Integration\DataFixtures\CurrencyFixture;
use Tests\SpearDevs\SyliusPushNotificationsPlugin\Integration\DataFixtures\CustomerFixture;
use Tests\SpearDevs\SyliusPushNotificationsPlugin\Integration\DataFixtures\LocaleFixture;
use Tests\SpearDevs\SyliusPushNotificationsPlugin\Integration\DataFixtures\ShopUserFixture;
use Tests\SpearDevs\SyliusPushNotificationsPlugin\Integration\DataFixtures\UserSubscriptionFixture;
use Tests\SpearDevs\SyliusPushNotificationsPlugin\Integration\IntegrationTestCase;

final class UserSubscriptionManagerTest extends IntegrationTestCase
{
    private UserSubscriptionManager $manager;

    private MySQLUserSubscriptionRepository $userSubscriptionRepository;

    private CustomerRepository $customerRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->manager = self::getContainer()->get(UserSubscriptionManager::class);
        $this->userSubscriptionRepository = self::getContainer()->get(MySQLUserSubscriptionRepository::class);
        $this->customerRepository = self::getContainer()->get(CustomerRepository::class);
    }

    public function testFactory(): void
    {
        //Given
        $user = new ShopUser();

        //When
        $result = $this->manager->factory($user, 'random_hash', []);

        //Then
        self::assertInstanceOf(UserSubscriptionInterface::class, $result);
    }

    public function testHash(): void
    {
        //Given
        $user = new ShopUser();

        //When
        $result = $this->manager->hash('https://example.com', $user);
        $isCorrectMd5 = (bool) preg_match('/^[a-f0-9]{32}$/', $result);

        //Then
        self::assertTrue($isCorrectMd5);
    }

    public function testDelete(): void
    {
        //Given
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

        $customer = $this->customerRepository->findOneBy(['email' => CustomerFixture::CUSTOMER_EMAIL]);

        $userSubscription = $this->userSubscriptionRepository->findOneBy(['customer' => $customer]);

        //When
        $this->manager->delete($userSubscription);

        $userSubscriptionAfterDelete = $this->userSubscriptionRepository->findOneBy(['customer' => $customer]);

        //Then
        self::assertNull($userSubscriptionAfterDelete);
    }
}
