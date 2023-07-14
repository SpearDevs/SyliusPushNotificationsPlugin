<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\Entity\PushNotificationConfiguration;

use Sylius\Component\Resource\Model\ResourceInterface;
use Symfony\Component\HttpFoundation\File\File;

interface PushNotificationConfigurationInterface extends ResourceInterface
{
    public function getIconPath(): ?string;

    public function setIconPath(?string $iconPath): void;

    public function getIcon(): ?File;

    public function setIcon(?File $icon): void;

    public function hasIcon(): bool;
}
