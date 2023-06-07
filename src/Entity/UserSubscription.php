<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\Entity;

use Doctrine\ORM\Mapping as ORM;

use Sylius\Component\User\Model\User;
use Sylius\Component\Core\Model\ShopUser;
use Symfony\Component\Security\Core\User\UserInterface;
use BenTools\WebPushBundle\Model\Subscription\UserSubscriptionInterface;

/**
 * @ORM\Entity()
 * @ORM\Table(name="web_push_user_subscription")
 */
class UserSubscription implements UserSubscriptionInterface
{
    /**
     * @var int
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="Sylius\Component\User\Model\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private User $user;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private string $subscriptionHash;

    /**
     * @var array
     *
     * @ORM\Column(type="json")
     */
    private array $subscription;

    /**
     * UserSubscription constructor.
     * @param ShopUser $user
     * @param string $subscriptionHash
     * @param array $subscription
     */
    public function __construct(ShopUser $user, string $subscriptionHash, array $subscription)
    {
        $this->user = $user;
        $this->subscriptionHash = $subscriptionHash;
        $this->subscription = $subscription;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @inheritDoc
     */
    public function getUser(): UserInterface
    {
        return $this->user;
    }

    /**
     * @inheritDoc
     */
    public function getSubscriptionHash(): string
    {
        return $this->subscriptionHash;
    }

    /**
     * @inheritDoc
     */
    public function getEndpoint(): string
    {
        return $this->subscription['endpoint'];
    }

    /**
     * @inheritDoc
     */
    public function getPublicKey(): string
    {
        return $this->subscription['keys']['p256dh'];
    }

    /**
     * @inheritDoc
     */
    public function getAuthToken(): string
    {
        return $this->subscription['keys']['auth'];
    }

    /**
     * Content-encoding (default: aesgcm).
     *
     * @return string
     */
    public function getContentEncoding(): string
    {
        return $this->subscription['content-encoding'] ?? 'aesgcm';
    }
}
