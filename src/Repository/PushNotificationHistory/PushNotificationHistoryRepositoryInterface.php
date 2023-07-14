<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\Repository\PushNotificationHistory;

use Doctrine\ORM\QueryBuilder;
use SpearDevs\SyliusPushNotificationsPlugin\Entity\PushNotificationHistory\PushNotificationHistoryInterface;
use Sylius\Component\Core\Model\ShopUserInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

interface PushNotificationHistoryRepositoryInterface extends RepositoryInterface
{
    public function createOwnedByUserQueryBuilder(ShopUserInterface $user): QueryBuilder;

    public function getCountOfNotReceivedPushNotifications(ShopUserInterface $user): int;

    public function save(PushNotificationHistoryInterface $pushNotificationHistory): void;
}
