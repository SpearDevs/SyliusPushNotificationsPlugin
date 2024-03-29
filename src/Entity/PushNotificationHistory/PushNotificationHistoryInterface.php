<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\Entity\PushNotificationHistory;

use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\ShopUserInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

interface PushNotificationHistoryInterface extends ResourceInterface
{
    public const STATE_NOT_RECEIVED = 'not_received';

    public const STATE_RECEIVED = 'received';

    public const RESPONSE_CREATED_CODE = 201;

    public function getTitle(): string;

    public function setTitle(string $title): void;

    public function getContent(): string;

    public function setContent(string $content): void;

    public function getUser(): ShopUserInterface;

    public function setUser(ShopUserInterface $user): void;

    public function getChannel(): ChannelInterface;

    public function setChannel(ChannelInterface $channel): void;

    public function getState(): string;

    public function setState(string $state): void;

    public function getResponseStatusCode(): int;

    public function setResponseStatusCode(int $responseStatusCode): void;
}
