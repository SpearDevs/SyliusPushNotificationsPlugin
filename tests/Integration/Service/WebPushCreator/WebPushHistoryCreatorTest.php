<?php

declare(strict_types=1);

namespace Tests\SpearDevs\SyliusPushNotificationsPlugin\Integration\Service\WebPushCreator;

use BenTools\WebPushBundle\Model\Response\PushResponse;
use SpearDevs\SyliusPushNotificationsPlugin\Context\ChannelContext;
use SpearDevs\SyliusPushNotificationsPlugin\Repository\MySQLUserSubscriptionRepository;
use SpearDevs\SyliusPushNotificationsPlugin\Repository\PushNotificationHistory\PushNotificationHistoryRepository;
use SpearDevs\SyliusPushNotificationsPlugin\Service\WebPushHistoryCreator\WebPushHistoryCreator;
use SpearDevs\SyliusPushNotificationsPlugin\WebPush\WebPush;
use Sylius\Bundle\CoreBundle\Doctrine\ORM\CustomerRepository;
use Tests\SpearDevs\SyliusPushNotificationsPlugin\Integration\DataFixtures\ChannelFixture;
use Tests\SpearDevs\SyliusPushNotificationsPlugin\Integration\DataFixtures\CurrencyFixture;
use Tests\SpearDevs\SyliusPushNotificationsPlugin\Integration\DataFixtures\CustomerFixture;
use Tests\SpearDevs\SyliusPushNotificationsPlugin\Integration\DataFixtures\LocaleFixture;
use Tests\SpearDevs\SyliusPushNotificationsPlugin\Integration\DataFixtures\ShopUserFixture;
use Tests\SpearDevs\SyliusPushNotificationsPlugin\Integration\DataFixtures\UserSubscriptionFixture;
use Tests\SpearDevs\SyliusPushNotificationsPlugin\Integration\IntegrationTestCase;

final class WebPushHistoryCreatorTest extends IntegrationTestCase
{
    private WebPushHistoryCreator $webPushHistoryCreator;

    private MySQLUserSubscriptionRepository $userSubscriptionRepository;

    private CustomerRepository $customerRepository;

    private ChannelContext $channelContext;

    private PushNotificationHistoryRepository $pushNotificationHistoryRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->webPushHistoryCreator = self::getContainer()->get(WebPushHistoryCreator::class);
        $this->userSubscriptionRepository = self::getContainer()->get(MySQLUserSubscriptionRepository::class);
        $this->customerRepository = self::getContainer()->get(CustomerRepository::class);
        $this->channelContext = self::getContainer()->get(ChannelContext::class);
        $this->pushNotificationHistoryRepository = self::getContainer()->get(PushNotificationHistoryRepository::class);
    }

    public function testCreate(): void
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

        $webPush = new WebPush(
            'Test',
            'Test',
        );

        $customer = $this->customerRepository->findOneBy(['email' => CustomerFixture::CUSTOMER_EMAIL]);
        $userSubscription = $this->userSubscriptionRepository->findOneBy(['customer' => $customer]);
        $pushResponse = new PushResponse($userSubscription, 201);
        $this->channelContext->setChannelCode($userSubscription->getChannel()->getCode());

        //Then
        $resultBeforeCreate = $this->pushNotificationHistoryRepository->findAll();

        self::assertCount(0, $resultBeforeCreate);

        //When
        $this->webPushHistoryCreator->create($webPush, $pushResponse);

        $resultAfterCreate = $this->pushNotificationHistoryRepository->findAll();

        self::assertCount(1, $resultAfterCreate);
    }
}
