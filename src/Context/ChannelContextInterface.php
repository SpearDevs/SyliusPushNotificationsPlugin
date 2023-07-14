<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\Context;

use Sylius\Component\Channel\Context\ChannelContextInterface as SyliusChannelContextInterface;

interface ChannelContextInterface extends SyliusChannelContextInterface
{
    public function setChannelCode(?string $channelCode): void;

    public function getChannelCode(): ?string;
}
