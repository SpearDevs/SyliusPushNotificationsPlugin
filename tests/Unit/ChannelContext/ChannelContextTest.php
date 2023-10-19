<?php

declare(strict_types=1);

namespace Tests\SpearDevs\SyliusPushNotificationsPlugin\Unit\ChannelContext;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SpearDevs\SyliusPushNotificationsPlugin\Context\ChannelContext;
use Sylius\Component\Channel\Context\ChannelNotFoundException;
use Sylius\Component\Channel\Model\ChannelInterface;
use Sylius\Component\Channel\Repository\ChannelRepositoryInterface;

final class ChannelContextTest extends TestCase
{
    /** @var ChannelRepositoryInterface&MockObject */
    private ChannelRepositoryInterface $channelRepository;

    private ChannelContext $channelContext;

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
        //Given
        $channelCode = 'channel_code';

        $this->expectException(ChannelNotFoundException::class);

        //Then
        $this->channelRepository->expects(self::once())
            ->method('findOneByCode')
            ->with($channelCode)
            ->willReturn(null);

        //When
        $this->channelContext->setChannelCode($channelCode);
        $this->channelContext->getChannel();
    }

    public function testMethodCorrectlyGetChannel(): void
    {
        //Given
        $channelCode = 'channel_code';
        $channel = $this->createMock(ChannelInterface::class);

        $this->channelRepository->expects(self::once())
            ->method('findOneByCode')
            ->with($channelCode)
            ->willReturn($channel);

        $this->channelContext->setChannelCode($channelCode);

        //When
        $result = $this->channelContext->getChannel();

        //Then
        Assert::assertSame($channel, $result);
    }
}
