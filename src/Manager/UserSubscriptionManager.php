<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\Manager;

use BenTools\WebPushBundle\Model\Subscription\UserSubscriptionInterface;
use BenTools\WebPushBundle\Model\Subscription\UserSubscriptionManagerInterface;
use SpearDevs\SyliusPushNotificationsPlugin\Entity\UserSubscription\UserSubscription;
use SpearDevs\SyliusPushNotificationsPlugin\Repository\UserSubscriptionRepositoryInterface;
use Sylius\Component\Core\Model\ShopUser;
use Symfony\Component\Security\Core\User\UserInterface;

final class UserSubscriptionManager implements UserSubscriptionManagerInterface
{
    public function __construct(
        private UserSubscriptionRepositoryInterface $userSubscriptionRepository
    ) {
    }

    /**
     * @inheritDoc
     */
    public function factory(
        UserInterface $user,
        string $subscriptionHash,
        array $subscription,
        array $options = []
    ): UserSubscriptionInterface {
        /** @var $user ShopUser */
        return new UserSubscription($user, $subscriptionHash, $subscription);
    }

    /**
     * @inheritDoc
     */
    public function hash(string $endpoint, UserInterface $user): string
    {
        return md5($endpoint);
    }

    /**
     * @inheritDoc
     */
    public function getUserSubscription(UserInterface $user, string $subscriptionHash): ?UserSubscriptionInterface
    {
        return $this->userSubscriptionRepository->findOneBy([
            'user' => $user,
            'subscriptionHash' => $subscriptionHash,
        ]);
    }

    /**
     * @inheritDoc
     */
    public function findByUser(UserInterface $user): iterable
    {
        return $this->userSubscriptionRepository->findBy([
            'user' => $user,
        ]);
    }

    /**
     * @inheritDoc
     */
    public function findByHash(string $subscriptionHash): iterable
    {
        return $this->userSubscriptionRepository->findBy([
            'subscriptionHash' => $subscriptionHash,
        ]);
    }

    /**
     * @inheritDoc
     */
    public function save(UserSubscriptionInterface $userSubscription): void
    {
        $this->userSubscriptionRepository->save($userSubscription);
    }

    /**
     * @inheritDoc
     */
    public function delete(UserSubscriptionInterface $userSubscription): void
    {
        $this->userSubscriptionRepository->delete($userSubscription);
    }
}
