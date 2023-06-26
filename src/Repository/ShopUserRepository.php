<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\Repository;

use SpearDevs\SyliusPushNotificationsPlugin\Entity\UserSubscription;
use Sylius\Bundle\CoreBundle\Doctrine\ORM\UserRepository;

final class ShopUserRepository extends UserRepository
{
    public function findUsersWithSubscriptionByGroup(?string $groupName): iterable
    {
        return $this->getQueryUsersWithSubscription()
            ->join('customer.group', 'g')
            ->andWhere('g.name = :groupName')
            ->setParameter('groupName', $groupName)
            ->getQuery()
            ->toIterable();
    }

    public function findAllUsersWithSubscription(): iterable
    {
        return $this->getQueryUsersWithSubscription()
            ->getQuery()
            ->toIterable();
    }

    private function getQueryUsersWithSubscription(): \Doctrine\ORM\QueryBuilder
    {
        return $this->createQueryBuilder('shopUser')
            ->select('shopUser')
            ->leftJoin(
                UserSubscription::class,
                'userSubscription',
                'WITH',
                'shopUser.id = userSubscription.user'
            )
            ->where('userSubscription.user is not null')
            ->join('shopUser.customer', 'customer');
    }
}
