<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\Manager;

use BenTools\WebPushBundle\Model\Subscription\UserSubscriptionInterface;
use BenTools\WebPushBundle\Model\Subscription\UserSubscriptionManagerInterface;
use SpearDevs\SyliusPushNotificationsPlugin\Entity\UserSubscription\UserSubscription;
use SpearDevs\SyliusPushNotificationsPlugin\Entity\UserSubscription\UserSubscription as SpearDevsUserSubscriptionInterface;
use SpearDevs\SyliusPushNotificationsPlugin\Repository\UserSubscriptionRepositoryInterface;
use Sylius\Component\Channel\Repository\ChannelRepositoryInterface;
use Sylius\Component\Core\Model\Channel;
use Sylius\Component\Core\Model\ShopUser;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\User\UserInterface;

class UserSubscriptionManager implements UserSubscriptionManagerInterface
{
    public function __construct(
        private UserSubscriptionRepositoryInterface $userSubscriptionRepository,
        private ChannelRepositoryInterface $channelRepository,
        private RequestStack $request,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function factory(
        UserInterface $user,
        string $subscriptionHash,
        array $subscription,
        array $options = [],
    ): UserSubscriptionInterface {
        /** @var ShopUser $user */
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
        $hostName = $this->request->getCurrentRequest()->getHttpHost();

        /** @var Channel $channel * */
        $channel = $this->channelRepository->findOneEnabledByHostname($hostName);

        /** @var SpearDevsUserSubscriptionInterface $userSubscription */
        $userSubscription->setChannel($channel);

        $userSubscription->setCustomer($userSubscription->getUser()->getCustomer());

        $this->userSubscriptionRepository->add($userSubscription);
    }

    /**
     * @inheritDoc
     */
    public function delete(UserSubscriptionInterface $userSubscription): void
    {
        /** @var SpearDevsUserSubscriptionInterface $userSubscription */
        $this->userSubscriptionRepository->remove($userSubscription);
    }
}
