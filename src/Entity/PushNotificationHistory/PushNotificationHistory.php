<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\Entity\PushNotificationHistory;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Sylius\Component\Core\Model\Channel;
use Sylius\Component\User\Model\User;

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
     * @ORM\ManyToOne(targetEntity="Sylius\Component\Core\Model\ShopUser")
     *
     * @ORM\JoinColumn(nullable=false)
     */
    private User $user;

    /**
     * @ORM\ManyToOne(targetEntity="Sylius\Component\Core\Model\Channel")
     *
     * @ORM\JoinColumn(nullable=false)
     */
    private Channel $channel;

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

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getChannel(): Channel
    {
        return $this->channel;
    }

    public function setChannel(Channel $channel): void
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
