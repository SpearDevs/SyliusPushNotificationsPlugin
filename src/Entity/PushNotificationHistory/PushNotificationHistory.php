<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\Entity\PushNotificationHistory;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\ShopUserInterface;

/**
 * @ORM\Entity
 *
 * @ORM\Table(name="speardevs_push_notification_history")
 */
class PushNotificationHistory implements PushNotificationHistoryInterface
{
    use TimestampableEntity;

    /**
     * @ORM\Column(type="integer")
     *
     * @ORM\Id
     *
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private int $id;

    /** @ORM\Column(type="string") */
    private string $title;

    /** @ORM\Column(type="text") */
    private string $content;

    /** @ORM\Column(type="string") */
    private string $state = self::STATE_NOT_RECEIVED;

    /** @ORM\Column(type="integer") */
    private int $responseStatusCode;

    /**
     * @ORM\ManyToOne(targetEntity="Sylius\Component\Core\Model\ShopUserInterface")
     *
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private ShopUserInterface $user;

    /**
     * @ORM\ManyToOne(targetEntity="Sylius\Component\Core\Model\ChannelInterface")
     *
     * @ORM\JoinColumn(nullable=false)
     */
    private ChannelInterface $channel;

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function setState(string $state): void
    {
        $this->state = $state;
    }

    public function getUser(): ShopUserInterface
    {
        return $this->user;
    }

    public function setUser(ShopUserInterface $user): void
    {
        $this->user = $user;
    }

    public function getChannel(): ChannelInterface
    {
        return $this->channel;
    }

    public function setChannel(ChannelInterface $channel): void
    {
        $this->channel = $channel;
    }

    public function getResponseStatusCode(): int
    {
        return $this->responseStatusCode;
    }

    public function setResponseStatusCode(int $responseStatusCode): void
    {
        $this->responseStatusCode = $responseStatusCode;
    }
}
