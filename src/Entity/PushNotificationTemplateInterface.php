<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\Entity;

use Sylius\Component\Resource\Model\ResourceInterface;

interface PushNotificationTemplateInterface extends ResourceInterface
{
    public function getTitle(): string;

    public function setTitle(string $title): void;

    public function getContent(): string;

    public function setContent(string $content): void;

    public function getCode(): string;

    public function setCode(string $code): void;
}
