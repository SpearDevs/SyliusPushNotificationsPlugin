<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\Context;

use Sylius\Component\Channel\Context\ChannelNotFoundException;
use Sylius\Component\Channel\Model\ChannelInterface;
use Sylius\Component\Channel\Repository\ChannelRepositoryInterface;

final class ChannelContext implements ChannelContextInterface
{
    private ?string $channelCode = null;

    public function __construct(
        private ChannelRepositoryInterface $channelRepository,
    ) {
    }

    public function setChannelCode(?string $channelCode): void
    {
        $this->channelCode = $channelCode;
    }

    public function getChannelCode(): ?string
    {
        return $this->channelCode;
    }

    public function getChannel(): ChannelInterface
    {
        if (null === $this->channelCode) {
            throw new ChannelNotFoundException();
        }

        $channel = $this->channelRepository->findOneByCode($this->channelCode);

        if (null === $channel) {
            throw new ChannelNotFoundException();
        }

        return $channel;
    }
}
