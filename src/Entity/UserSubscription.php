<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\Entity;

use Doctrine\ORM\Mapping as ORM;

use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\User\Model\User;
use Sylius\Component\Core\Model\ShopUser;
use Symfony\Component\Security\Core\User\UserInterface;
use BenTools\WebPushBundle\Model\Subscription\UserSubscriptionInterface;

/**
 * @ORM\Entity(repositoryClass="SpearDevs\SyliusPushNotificationsPlugin\Repository\MySQLUserSubscriptionRepository")
 * @ORM\Table(name="web_push_user_subscription")
 */
class UserSubscription implements UserSubscriptionInterface, ResourceInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="Sylius\Component\Core\Model\ShopUser")
     * @ORM\JoinColumn(nullable=false)
     */
    private User $user;

    /**
     * @ORM\Column(type="string")
     */
    private string $subscriptionHash;

    /**
     * @ORM\Column(type="json")
     */
    private array $subscriptions;

    public function __construct(ShopUser $user, string $subscriptionHash, array $subscriptions)
    {
        $this->user = $user;
        $this->subscriptionHash = $subscriptionHash;
        $this->subscriptions = $subscriptions;
    }

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
        return $this->subscriptions['endpoint'] ?? '';
    }

    /**
     * @inheritDoc
     */
    public function getPublicKey(): string
    {
        return $this->subscriptions['keys']['p256dh'] ?? '';
    }

    /**
     * @inheritDoc
     */
    public function getAuthToken(): string
    {
        return $this->subscriptions['keys']['auth'] ?? '';
    }

    public function getContentEncoding(): string
    {
        return $this->subscriptions['content-encoding'] ?? 'aesgcm';
    }
}
