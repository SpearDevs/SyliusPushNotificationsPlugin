<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\Repository;

use Sylius\Bundle\CoreBundle\Doctrine\ORM\UserRepository;
use Sylius\Component\Customer\Model\CustomerGroupInterface;

final class ShopUserRepository extends UserRepository
{
    public function findUsersByGroup(?CustomerGroupInterface $customerGroup): array
    {
        $queryBuilder = $this->createQueryBuilder('shopUser')
            ->select('shopUser');

        if ($customerGroup !== null) {
            $queryBuilder
                ->join('shopUser.customer', 'customer')
                ->join('customer.group', 'g')
                ->where('g.name = :groupName')
                ->setParameter('groupName', $customerGroup->getName());
        }

        return $queryBuilder
            ->getQuery()
            ->getResult();
    }
}
