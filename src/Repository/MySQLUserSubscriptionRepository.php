<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\Repository;

use Doctrine\ORM\QueryBuilder;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

final class MySQLUserSubscriptionRepository extends EntityRepository implements UserSubscriptionRepositoryInterface
{
    public function getSubscriptionsForAllUsers(): iterable
    {
        return $this->getQueryToGetUserSubscriptions()
            ->getQuery()
            ->toIterable();
    }

    public function getSubscriptionsForUsersInGroup(string $groupName): iterable
    {
        return $this->getQueryToGetUserSubscriptions()
            ->join('customer.group', 'g')
            ->andWhere('g.name = :groupName')
            ->setParameter('groupName', $groupName)
            ->getQuery()
            ->toIterable();
    }

    public function getSubscriptionsForUserByEmail(string $email): iterable
    {
        return $this->getQueryToGetUserSubscriptions()
            ->where('user.username = :email')
            ->setParameter('email', $email)
            ->getQuery()
            ->toIterable();
    }

    private function getQueryToGetUserSubscriptions(): QueryBuilder
    {
        return $this->createQueryBuilder('userSubscription')
            ->select('userSubscription')
            ->leftJoin(
                'userSubscription.user',
                'user',
                'WITH',
                'user.id = userSubscription.user',
            )
            ->where('userSubscription.user is not null')
            ->join('user.customer', 'customer');
    }
}
