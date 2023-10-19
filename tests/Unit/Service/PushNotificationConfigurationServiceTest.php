<?php

declare(strict_types=1);

namespace Tests\SpearDevs\SyliusPushNotificationsPlugin\Unit\Service;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SpearDevs\SyliusPushNotificationsPlugin\Context\ChannelContextInterface;
use SpearDevs\SyliusPushNotificationsPlugin\Entity\PushNotificationConfiguration\PushNotificationConfigurationInterface;
use SpearDevs\SyliusPushNotificationsPlugin\Repository\PushNotificationConfiguration\PushNotificationConfigurationRepositoryInterface;
use SpearDevs\SyliusPushNotificationsPlugin\Service\PushNotificationConfigurationService;
use Sylius\Component\Core\Model\Channel;

final class PushNotificationConfigurationServiceTest extends TestCase
{
    /** @var PushNotificationConfigurationRepositoryInterface&MockObject */
    private PushNotificationConfigurationRepositoryInterface $pushNotificationConfigurationRepository;

    /** @var ChannelContextInterface&MockObject */
    private ChannelContextInterface $channelContext;

    private string $appScheme;

    private string $imagesDirectory;

    /** @var PushNotificationConfigurationService&MockObject */
    private PushNotificationConfigurationService $pushNotificationConfigurationService;

    protected function setUp(): void
    {
        $this->pushNotificationConfigurationRepository = $this->createMock(PushNotificationConfigurationRepositoryInterface::class);
        $this->channelContext = $this->createMock(ChannelContextInterface::class);
        $this->appScheme = 'https';
        $this->imagesDirectory = '/media/image';

        $this->pushNotificationConfigurationService = new PushNotificationConfigurationService(
            $this->pushNotificationConfigurationRepository,
            $this->channelContext,
            $this->appScheme,
            $this->imagesDirectory,
        );
    }

    public function testGetLinkToPushNotificationIconWithConfiguration()
    {
        //Given
        $channel = $this->createMock(Channel::class);
        $pushNotificationConfiguration = $this->createMock(PushNotificationConfigurationInterface::class);
        $expectedLink = 'https://example.com/media/image/icon.png';

        $this->channelContext->expects($this->once())
            ->method('getChannel')
            ->willReturn($channel);

        $this->pushNotificationConfigurationRepository->expects($this->once())
            ->method('findOneBy')
            ->with(['channel' => $channel->getId()])
            ->willReturn($pushNotificationConfiguration);

        $channel->expects($this->once())
            ->method('getHostname')
            ->willReturn('example.com');

        $pushNotificationConfiguration->expects($this->once())
            ->method('getIconPath')
            ->willReturn('/icon.png');

        //WHen
        $link = $this->pushNotificationConfigurationService->getLinkToPushNotificationIcon();

        //Then
        $this->assertEquals($expectedLink, $link);
    }

    public function testGetLinkToPushNotificationIconWithoutConfiguration()
    {
        //Given
        $channel = $this->createMock(Channel::class);

        $this->channelContext->expects($this->once())
            ->method('getChannel')
            ->willReturn($channel);

        $this->pushNotificationConfigurationRepository->expects($this->once())
            ->method('findOneBy')
            ->with(['channel' => $channel->getId()])
            ->willReturn(null);

        //When
        $link = $this->pushNotificationConfigurationService->getLinkToPushNotificationIcon();

        //Then
        $this->assertNull($link);
    }
}
