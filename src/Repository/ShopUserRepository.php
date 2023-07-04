<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\Repository;

use Sylius\Bundle\CoreBundle\Doctrine\ORM\UserRepository;

final class ShopUserRepository extends UserRepository
{
    public function findUsersByGroup(?string $groupName): iterable
    {
        $queryBuilder = $this->createQueryBuilder('shopUser')->select('shopUser');

        if ($groupName !== '') {
            $queryBuilder
                ->join('shopUser.customer', 'customer')
                ->join('customer.group', 'g')
                ->where('g.name = :groupName')
                ->setParameter('groupName', $groupName);
        }

        return $queryBuilder
            ->getQuery()
            ->toIterable();
    }
}
