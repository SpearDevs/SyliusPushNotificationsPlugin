<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\Repository;

use BenTools\WebPushBundle\Model\Subscription\UserSubscriptionInterface;
use Doctrine\Persistence\ManagerRegistry;
use SpearDevs\SyliusPushNotificationsPlugin\Entity\UserSubscription;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

final class MySQLUserSubscriptionRepository extends ServiceEntityRepository implements UserSubscriptionRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserSubscription::class);
    }

    /**
     * @inheritDoc
     */
    public function save(UserSubscriptionInterface $userSubscription): void
    {
        $this->_em->persist($userSubscription);
        $this->_em->flush();
    }

    /**
     * @inheritDoc
     */
    public function delete(UserSubscriptionInterface $userSubscription): void
    {
        $this->_em->remove($userSubscription);
        $this->_em->flush();
    }
}
