<?php

declare(strict_types=1);

namespace Tests\SpearDevs\SyliusPushNotificationsPlugin\Unit\Factory;

use BenTools\WebPushBundle\Model\Response\PushResponse;
use BenTools\WebPushBundle\Model\Subscription\UserSubscriptionInterface;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SpearDevs\SyliusPushNotificationsPlugin\Context\ChannelContextInterface;
use SpearDevs\SyliusPushNotificationsPlugin\Entity\PushNotificationHistory\PushNotificationHistory;
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

    public function testCreateNew(): void
    {
        //Given
        $pushNotificationHistory = $this->createMock(PushNotificationHistoryInterface::class);

        $this->factory->expects(self::once())
            ->method('createNew')
            ->willReturn($pushNotificationHistory);

        //When
        $result = $this->pushNotificationHistoryFactory->createNew();

        //Then
        $this->assertInstanceOf(PushNotificationHistoryInterface::class, $result);
    }

    public function testCreateNewWithPushNotificationData(): void
    {
        //Given
        $pushTitle = 'Test Title';
        $pushContent = 'Test Content';
        $responseStatusCode = 200;
        $subscription = $this->createMock(UserSubscriptionInterface::class);
        $pushNotificationHistory = new PushNotificationHistory();

        $pushResponse = new PushResponse($subscription, $responseStatusCode);

        $user = $this->createMock(ShopUserInterface::class);

        $this->factory->expects(self::once())
            ->method('createNew')
            ->willReturn($pushNotificationHistory);

        $subscription->expects(self::once())
            ->method('getUser')
            ->willReturn($user);

        $this->channelContext->expects(self::once())
            ->method('getChannel')
            ->willReturn($this->createMock(Channel::class));

        //When
        $result = $this->pushNotificationHistoryFactory->createNewWithPushNotificationData(
            $pushTitle,
            $pushContent,
            $pushResponse,
        );

        //Then
        Assert::assertInstanceOf(PushNotificationHistoryInterface::class, $result);
        Assert::assertSame($pushTitle, $pushNotificationHistory->getTitle());
        Assert::assertSame($pushContent, $pushNotificationHistory->getContent());
        Assert::assertSame($responseStatusCode, $pushNotificationHistory->getResponseStatusCode());
    }
}
