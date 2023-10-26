<?php

declare(strict_types=1);

namespace Tests\SpearDevs\SyliusPushNotificationsPlugin\Unit\Service;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SpearDevs\SyliusPushNotificationsPlugin\Context\ChannelContextInterface;
use SpearDevs\SyliusPushNotificationsPlugin\Entity\PushNotificationConfiguration\PushNotificationConfigurationInterface;
use SpearDevs\SyliusPushNotificationsPlugin\Repository\PushNotificationConfiguration\PushNotificationConfigurationRepositoryInterface;
use SpearDevs\SyliusPushNotificationsPlugin\Service\PushNotificationConfigurationService;
use Sylius\Component\Channel\Model\ChannelInterface;

final class PushNotificationConfigurationServiceTest extends TestCase
{
    /** @var PushNotificationConfigurationRepositoryInterface&MockObject */
    private PushNotificationConfigurationRepositoryInterface $pushNotificationConfigurationRepository;

    /** @var ChannelContextInterface&MockObject */
    private ChannelContextInterface $channelContext;

    private PushNotificationConfigurationService $pushNotificationConfigurationService;

    protected function setUp(): void
    {
        $this->pushNotificationConfigurationRepository = $this->createMock(PushNotificationConfigurationRepositoryInterface::class);
        $this->channelContext = $this->createMock(ChannelContextInterface::class);

        $this->pushNotificationConfigurationService = new PushNotificationConfigurationService(
            $this->pushNotificationConfigurationRepository,
            $this->channelContext,
            'https',
            '/media/image',
        );
    }

    public function testGetLinkToPushNotificationIconWithConfiguration(): void
    {
        //Given
        $channel = $this->createMock(ChannelInterface::class);
        $pushNotificationConfiguration = $this->createMock(PushNotificationConfigurationInterface::class);
        $expectedLink = 'https://example.com/media/image/icon.png';

        $this->channelContext->expects(self::once())
            ->method('getChannel')
            ->willReturn($channel);

        $this->pushNotificationConfigurationRepository->expects(self::once())
            ->method('findOneBy')
            ->with(['channel' => $channel->getId()])
            ->willReturn($pushNotificationConfiguration);

        $channel->expects(self::once())
            ->method('getHostname')
            ->willReturn('example.com');

        $pushNotificationConfiguration->expects(self::once())
            ->method('getIconPath')
            ->willReturn('/icon.png');

        //WHen
        $link = $this->pushNotificationConfigurationService->getLinkToPushNotificationIcon();

        //Then
        Assert::assertEquals($expectedLink, $link);
    }

    public function testGetLinkToPushNotificationIconWithoutConfiguration(): void
    {
        //Given
        $channel = $this->createMock(ChannelInterface::class);

        $this->channelContext->expects(self::once())
            ->method('getChannel')
            ->willReturn($channel);

        $this->pushNotificationConfigurationRepository->expects(self::once())
            ->method('findOneBy')
            ->with(['channel' => $channel->getId()])
            ->willReturn(null);

        //When
        $link = $this->pushNotificationConfigurationService->getLinkToPushNotificationIcon();

        //Then
        Assert::assertNull($link);
    }
}
