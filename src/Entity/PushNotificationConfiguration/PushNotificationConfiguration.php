<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\Entity\PushNotificationConfiguration;

use Doctrine\ORM\Mapping as ORM;
use SpearDevs\SyliusPushNotificationsPlugin\Entity\Traits\EntityIdTrait;
use Symfony\Component\HttpFoundation\File\File;

/**
 * @ORM\Entity
 * @ORM\Table(name="speardevs_push_notification_configuration")
 */
class PushNotificationConfiguration implements PushNotificationConfigurationInterface
{
    use EntityIdTrait;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $iconPath = null;

    protected ?File $icon = null;

    public function getIconPath(): ?string
    {
        return $this->iconPath;
    }

    public function setIconPath(?string $iconPath): void
    {
        $this->iconPath = $iconPath;
    }

    public function getIcon(): ?File
    {
        return $this->icon;
    }

    public function setIcon(?File $icon): void
    {
        $this->icon = $icon;
    }

    public function hasIcon(): bool
    {
        return null !== $this->icon;
    }
}
