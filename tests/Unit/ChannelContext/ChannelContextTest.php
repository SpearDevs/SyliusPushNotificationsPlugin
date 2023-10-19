<?php

declare(strict_types=1);

namespace Tests\SpearDevs\SyliusPushNotificationsPlugin\Unit\ChannelContext;

use PHPUnit\Framework\TestCase;
use SpearDevs\SyliusPushNotificationsPlugin\Context\ChannelContext;
use Sylius\Component\Channel\Context\ChannelNotFoundException;
use Sylius\Component\Channel\Model\ChannelInterface;
use Sylius\Component\Channel\Repository\ChannelRepositoryInterface;
use Sylius\Component\Core\Model\Channel;

final class ChannelContextTest extends TestCase
{
    private ChannelRepositoryInterface $channelRepository;

    protected function setUp(): void
    {
        $this->channelRepository = $this->createMock(ChannelRepositoryInterface::class);
        $this->channelContext = new ChannelContext($this->channelRepository);
    }

    public function testThrowExceptionWhenChannelCodeIsNull(): void
    {
        $this->expectException(ChannelNotFoundException::class);

        $this->channelContext->setChannelCode(null);
        $this->channelContext->getChannel();
    }

    public function testThrowExceptionWhenChannelIsNull(): void
    {
        $channelCode = 'channel_code';

        $this->expectException(ChannelNotFoundException::class);

        $this->channelRepository->expects($this->once())
            ->method('findOneByCode')
            ->with($channelCode)
            ->willReturn(null);

        $this->channelContext->setChannelCode($channelCode);
        $this->channelContext->getChannel();
    }

    public function testMethodCorrectlyGetChannel(): void
    {
        $channelCode = 'channel_code';
        $channel = new Channel();

        $this->channelRepository->expects($this->once())
            ->method('findOneByCode')
            ->with($channelCode)
            ->willReturn($channel);

        $this->channelContext->setChannelCode($channelCode);
        $this->channelContext->getChannel();
        $this->assertInstanceOf(ChannelInterface::class, $channel);
    }
}
