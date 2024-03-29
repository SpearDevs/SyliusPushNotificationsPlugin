<?php

declare(strict_types=1);

namespace Tests\SpearDevs\SyliusPushNotificationsPlugin\Unit\Twig\Extension;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SpearDevs\SyliusPushNotificationsPlugin\Repository\PushNotificationHistory\PushNotificationHistoryRepositoryInterface;
use SpearDevs\SyliusPushNotificationsPlugin\Twig\Extension\PushNotificationHistoryTwigExtension;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\ShopUserInterface;

final class PushNotificationHistoryTwigExtensionTest extends TestCase
{
    /** @var PushNotificationHistoryRepositoryInterface&MockObject */
    private PushNotificationHistoryRepositoryInterface $pushNotificationHistoryRepository;

    /** @var ChannelContextInterface&MockObject */
    private ChannelContextInterface $channelContext;

    private PushNotificationHistoryTwigExtension $pushNotificationHistoryTwigExtension;

    public function setUp(): void
    {
        $this->pushNotificationHistoryRepository = $this->createMock(PushNotificationHistoryRepositoryInterface::class);
        $this->channelContext = $this->createMock(ChannelContextInterface::class);

        $this->pushNotificationHistoryTwigExtension = new PushNotificationHistoryTwigExtension(
            $this->pushNotificationHistoryRepository,
            $this->channelContext,
        );
    }

    public function testGetCountOfNotReceivedPushNotifications(): void
    {
        //Given
        $user = $this->createMock(ShopUserInterface::class);
        $channel = $this->createMock(ChannelInterface::class);

        $this->pushNotificationHistoryRepository->expects(self::once())
            ->method('getCountOfNotReceivedCustomerPushNotifications')
            ->with($user, $channel)
            ->willReturn(5);

        $this->channelContext->expects(self::once())
            ->method('getChannel')
            ->willReturn($channel);

        //When
        $result = $this->pushNotificationHistoryTwigExtension->getCountOfNotReceivedPushNotifications($user);

        //Then
        Assert::assertEquals(5, $result);
    }
}
