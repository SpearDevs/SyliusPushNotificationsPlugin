<?php

declare(strict_types=1);

namespace Tests\SpearDevs\SyliusPushNotificationsPlugin\Unit\Service\WebPushHistoryCreator;

use BenTools\WebPushBundle\Model\Response\PushResponse;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SpearDevs\SyliusPushNotificationsPlugin\Entity\PushNotificationHistory\PushNotificationHistoryInterface;
use SpearDevs\SyliusPushNotificationsPlugin\Factory\PushNotificationHistoryFactory;
use SpearDevs\SyliusPushNotificationsPlugin\Repository\PushNotificationHistory\PushNotificationHistoryRepositoryInterface;
use SpearDevs\SyliusPushNotificationsPlugin\Service\WebPushHistoryCreator\WebPushHistoryCreator;
use SpearDevs\SyliusPushNotificationsPlugin\WebPush\WebPushInterface;

final class WebPushHistoryCreatorTest extends TestCase
{
    /** @var PushNotificationHistoryFactory&MockObject */
    private PushNotificationHistoryFactory $pushNotificationHistoryFactory;

    /** @var PushNotificationHistoryRepositoryInterface&MockObject */
    private PushNotificationHistoryRepositoryInterface $pushNotificationHistoryRepository;

    private WebPushHistoryCreator $webPushHistoryCreator;

    protected function setUp(): void
    {
        $this->pushNotificationHistoryFactory = $this->createMock(PushNotificationHistoryFactory::class);
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
        $pushResponse = $this->createMock(PushResponse::class);

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
