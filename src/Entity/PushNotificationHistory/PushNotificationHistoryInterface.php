<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\Entity\PushNotificationHistory;

use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\User\Model\User;

interface PushNotificationHistoryInterface extends ResourceInterface
{
    public const STATE_NOT_RECEIVED = 'not_received';

    public const STATE_RECEIVED = 'received';

    public function getTitle(): string;

    public function setTitle(string $title): void;

    public function getContent(): string;

    public function setContent(string $content): void;

    public function getUser(): User;

    public function setUser(User $user): void;

    public function getState(): string;

    public function setState(string $state): void;

    public function getResponseStatusCode(): int;

    public function setResponseStatusCode(int $responseStatusCode): void;
}
