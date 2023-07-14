<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\Entity\UserSubscription;

use BenTools\WebPushBundle\Model\Subscription\UserSubscriptionInterface;
use Doctrine\ORM\Mapping as ORM;
use SpearDevs\SyliusPushNotificationsPlugin\Entity\Traits\EntityIdTrait;
use Sylius\Component\Core\Model\ShopUser;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\User\Model\User;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="SpearDevs\SyliusPushNotificationsPlugin\Repository\MySQLUserSubscriptionRepository")
 *
 * @ORM\Table(name="speardevs_web_push_user_subscription")
 */
class UserSubscription implements UserSubscriptionInterface, ResourceInterface
{
    use EntityIdTrait;

    /**
     * @ORM\ManyToOne(targetEntity="Sylius\Component\Core\Model\ShopUser")
     *
     * @ORM\JoinColumn(nullable=false)
     */
    private User $user;

    /** @ORM\Column(type="string") */
    private string $subscriptionHash;

    /** @ORM\Column(type="json") */
    private array $subscription;

    public function __construct(ShopUser $user, string $subscriptionHash, array $subscription)
    {
        $this->user = $user;
        $this->subscriptionHash = $subscriptionHash;
        $this->subscription = $subscription;
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
        return $this->subscription['endpoint'] ?? '';
    }

    /**
     * @inheritDoc
     */
    public function getPublicKey(): string
    {
        return $this->subscription['keys']['p256dh'] ?? '';
    }

    /**
     * @inheritDoc
     */
    public function getAuthToken(): string
    {
        return $this->subscription['keys']['auth'] ?? '';
    }

    public function getContentEncoding(): string
    {
        return $this->subscription['content-encoding'] ?? 'aesgcm';
    }
}
