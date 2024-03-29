<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\Entity\PushNotificationConfiguration;

use Doctrine\ORM\Mapping as ORM;
use SpearDevs\SyliusPushNotificationsPlugin\Entity\Traits\EntityIdTrait;
use Sylius\Component\Core\Model\ChannelInterface;
use Symfony\Component\HttpFoundation\File\File;

/**
 * @ORM\Entity
 *
 * @ORM\Table(name="speardevs_push_notification_configuration")
 */
class PushNotificationConfiguration implements PushNotificationConfigurationInterface
{
    use EntityIdTrait;

    /** @ORM\Column(type="string", nullable=true) */
    private ?string $iconPath = null;

    protected ?File $icon = null;

    /**
     * @ORM\ManyToOne(targetEntity="Sylius\Component\Core\Model\ChannelInterface")
     *
     * @ORM\JoinColumn(nullable=false)
     */
    private ChannelInterface $channel;

    public function __construct()
    {
        $this->setIconPath(null);
    }

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

    public function getChannel(): ChannelInterface
    {
        return $this->channel;
    }

    public function setChannel(ChannelInterface $channel): void
    {
        $this->channel = $channel;
    }
}
