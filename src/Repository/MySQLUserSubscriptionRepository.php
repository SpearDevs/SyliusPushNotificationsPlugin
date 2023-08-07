<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\Repository;

use Doctrine\ORM\QueryBuilder;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Component\Core\Model\ChannelInterface;

final class MySQLUserSubscriptionRepository extends EntityRepository implements UserSubscriptionRepositoryInterface
{
    public function getSubscriptionsForAllUsers(ChannelInterface $channel): iterable
    {
        return $this->getQueryToGetUserSubscriptions()
            ->andWhere('userSubscription.channel = :channel')
            ->setParameter('channel', $channel)
            ->getQuery()
            ->toIterable();
    }

    public function getSubscriptionsForUsersInGroup(string $groupName, ChannelInterface $channel): iterable
    {
        return $this->getQueryToGetUserSubscriptions()
            ->join('customer.group', 'g')
            ->andWhere('g.name = :groupName')
            ->setParameter('groupName', $groupName)
            ->andWhere('userSubscription.channel = :channel')
            ->setParameter('channel', $channel)
            ->getQuery()
            ->toIterable();
    }

    public function getSubscriptionsForUserByEmail(string $email, ChannelInterface $channel): iterable
    {
        return $this->getQueryToGetUserSubscriptions()
            ->where('customer.email = :email')
            ->setParameter('email', $email)
            ->andWhere('userSubscription.channel = :channel')
            ->setParameter('channel', $channel)
            ->getQuery()
            ->toIterable();
    }

    private function getQueryToGetUserSubscriptions(): QueryBuilder
    {
        return $this->createQueryBuilder('userSubscription')
            ->select('userSubscription')
            ->leftJoin(
                'userSubscription.customer',
                'customer',
                'WITH',
                'customer.id = userSubscription.customer',
            )
            ->where('userSubscription.customer is not null');
    }
}
