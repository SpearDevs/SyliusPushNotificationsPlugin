<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\Entity\UserSubscription;

use BenTools\WebPushBundle\Model\Subscription\UserSubscriptionInterface;
use Doctrine\ORM\Mapping as ORM;
use SpearDevs\SyliusPushNotificationsPlugin\Entity\Traits\EntityIdTrait;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Model\ShopUserInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
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
     * @ORM\ManyToOne(targetEntity="Sylius\Component\Core\Model\ShopUserInterface")
     *
     * @ORM\JoinColumn(nullable=false)
     */
    private ShopUserInterface $user;

    /**
     * @ORM\ManyToOne(targetEntity="Sylius\Component\Customer\Model\CustomerInterface")
     *
     * @ORM\JoinColumn(nullable=false, name="customer_id",)
     */
    private CustomerInterface $customer;

    /** @ORM\Column(type="string") */
    private string $subscriptionHash;

    /** @ORM\Column(type="json") */
    private array $subscription;

    /** @ORM\ManyToOne(targetEntity="Sylius\Component\Core\Model\ChannelInterface") */
    private ChannelInterface $channel;

    public function __construct(ShopUserInterface $user, string $subscriptionHash, array $subscription)
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

    public function getCustomer(): ?CustomerInterface
    {
        return $this->customer;
    }

    public function setCustomer(?CustomerInterface $customer): self
    {
        $this->customer = $customer;

        return $this;
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

    public function getChannel(): ChannelInterface
    {
        return $this->channel;
    }

    public function setChannel(ChannelInterface $channel): void
    {
        $this->channel = $channel;
    }
}
