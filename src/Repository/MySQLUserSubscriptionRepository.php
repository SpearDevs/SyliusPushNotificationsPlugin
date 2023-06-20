<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\Repository;

use BenTools\WebPushBundle\Model\Subscription\UserSubscriptionInterface;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

final class MySQLUserSubscriptionRepository extends EntityRepository implements UserSubscriptionRepositoryInterface
{
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
