<?php

declare(strict_types=1);

namespace Tests\SpearDevs\SyliusPushNotificationsPlugin\Unit\Factory;

use BenTools\WebPushBundle\Model\Response\PushResponse;
use BenTools\WebPushBundle\Model\Subscription\UserSubscriptionInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SpearDevs\SyliusPushNotificationsPlugin\Context\ChannelContextInterface;
use SpearDevs\SyliusPushNotificationsPlugin\Entity\PushNotificationHistory\PushNotificationHistoryInterface;
use SpearDevs\SyliusPushNotificationsPlugin\Factory\PushNotificationHistoryFactory;
use Sylius\Component\Core\Model\Channel;
use Sylius\Component\Core\Model\ShopUserInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

final class PushNotificationHistoryFactoryTest extends TestCase
{
    /** @var FactoryInterface&MockObject */
    private FactoryInterface $factory;

    /** @var ChannelContextInterface&MockObject */
    private ChannelContextInterface $channelContext;

    /** @var PushNotificationHistoryFactory&MockObject */
    private PushNotificationHistoryFactory $pushNotificationHistoryFactory;

    protected function setUp(): void
    {
        $this->factory = $this->createMock(FactoryInterface::class);
        $this->channelContext = $this->createMock(ChannelContextInterface::class);

        $this->pushNotificationHistoryFactory = new PushNotificationHistoryFactory(
            $this->factory,
            $this->channelContext,
        );
    }

    public function testCreateNew()
    {
        //Given
        $pushNotificationHistory = $this->createMock(PushNotificationHistoryInterface::class);

        $this->factory->expects($this->once())
            ->method('createNew')
            ->willReturn($pushNotificationHistory);

        //When
        $result = $this->pushNotificationHistoryFactory->createNew();

        //Then
        $this->assertInstanceOf(PushNotificationHistoryInterface::class, $result);
    }

    public function testCreateNewWithPushNotificationData()
    {
        //Given
        $pushTitle = 'Test Title';
        $pushContent = 'Test Content';
        $pushResponse = $this->createMock(PushResponse::class);
        $subscription = $this->createMock(UserSubscriptionInterface::class);
        $pushNotificationHistory = $this->createMock(PushNotificationHistoryInterface::class);

        $pushResponse->expects($this->once())
            ->method('getSubscription')
            ->willReturn($subscription);

        $user = $this->createMock(ShopUserInterface::class);

        $this->factory->expects($this->once())
            ->method('createNew')
            ->willReturn($pushNotificationHistory);

        $subscription->expects($this->once())
            ->method('getUser')
            ->willReturn($user);

        $this->channelContext->expects($this->once())
            ->method('getChannel')
            ->willReturn($this->createMock(Channel::class));

        //When
        $result = $this->pushNotificationHistoryFactory->createNewWithPushNotificationData(
            $pushTitle,
            $pushContent,
            $pushResponse,
        );

        //Then
        $this->assertInstanceOf(PushNotificationHistoryInterface::class, $result);
    }
}
