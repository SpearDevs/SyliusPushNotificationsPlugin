<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\Repository\PushNotificationHistory;

use Doctrine\ORM\QueryBuilder;
use SpearDevs\SyliusPushNotificationsPlugin\Entity\PushNotificationHistory\PushNotificationHistoryInterface;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Component\Core\Model\ShopUserInterface;

final class PushNotificationHistoryRepository extends EntityRepository implements PushNotificationHistoryRepositoryInterface
{
    public function createOwnedByUserQueryBuilder(ShopUserInterface $user): QueryBuilder
    {
        return $this->createQueryBuilder('pushNotificationHistory')
            ->innerJoin('pushNotificationHistory.user', 'user')
            ->where('user = :user')
            ->setParameter('user', $user);
    }

    public function getCountOfNotReceivedPushNotifications(ShopUserInterface $user): int
    {
        return $this->createQueryBuilder('pushNotificationHistory')
            ->select('COUNT(pushNotificationHistory)')
            ->innerJoin('pushNotificationHistory.user', 'user')
            ->where('user = :user')
            ->andWhere('pushNotificationHistory.state = :state')
            ->setParameter('user', $user)
            ->setParameter('state', PushNotificationHistoryInterface::STATE_NOT_RECEIVED)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function save(PushNotificationHistoryInterface $pushNotificationHistory): void
    {
        $this->_em->persist($pushNotificationHistory);
        $this->_em->flush();
    }
}
