<?php

declare(strict_types=1);

namespace Tests\SpearDevs\SyliusPushNotificationsPlugin\Unit\Service\WebPushHistoryCreator;

use BenTools\WebPushBundle\Model\Response\PushResponse;
use BenTools\WebPushBundle\Model\Subscription\UserSubscriptionInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SpearDevs\SyliusPushNotificationsPlugin\Entity\PushNotificationHistory\PushNotificationHistoryInterface;
use SpearDevs\SyliusPushNotificationsPlugin\Factory\PushNotificationHistoryFactoryInterface;
use SpearDevs\SyliusPushNotificationsPlugin\Repository\PushNotificationHistory\PushNotificationHistoryRepositoryInterface;
use SpearDevs\SyliusPushNotificationsPlugin\Service\WebPushHistoryCreator\WebPushHistoryCreator;
use SpearDevs\SyliusPushNotificationsPlugin\WebPush\WebPushInterface;

final class WebPushHistoryCreatorTest extends TestCase
{
    /** @var PushNotificationHistoryFactoryInterface&MockObject */
    private PushNotificationHistoryFactoryInterface $pushNotificationHistoryFactory;

    /** @var PushNotificationHistoryRepositoryInterface&MockObject */
    private PushNotificationHistoryRepositoryInterface $pushNotificationHistoryRepository;

    private WebPushHistoryCreator $webPushHistoryCreator;

    protected function setUp(): void
    {
        $this->pushNotificationHistoryFactory = $this->createMock(PushNotificationHistoryFactoryInterface::class);
        $this->pushNotificationHistoryRepository = $this->createMock(PushNotificationHistoryRepositoryInterface::class);

        $this->webPushHistoryCreator = new WebPushHistoryCreator(
            $this->pushNotificationHistoryFactory,
            $this->pushNotificationHistoryRepository,
        );
    }

    public function testCreate(): void
    {
        //Given
        $webPush = $this->createMock(WebPushInterface::class);
        $responseStatusCode = 200;
        $subscription = $this->createMock(UserSubscriptionInterface::class);
        $pushResponse = new PushResponse($subscription, $responseStatusCode);

        $pushNotificationHistory = $this->createMock(PushNotificationHistoryInterface::class);

        //Then
        $this->pushNotificationHistoryFactory->expects(self::once())
            ->method('createNewWithPushNotificationData')
            ->with($webPush->getTitle(), $webPush->getContent(), $pushResponse)
            ->willReturn($pushNotificationHistory);

        $this->pushNotificationHistoryRepository->expects(self::once())
            ->method('save')
            ->with($pushNotificationHistory);

        //When
        $this->webPushHistoryCreator->create($webPush, $pushResponse);
    }
}
