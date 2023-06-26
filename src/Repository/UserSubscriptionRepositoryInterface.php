<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\Repository;

use BenTools\WebPushBundle\Model\Subscription\UserSubscriptionInterface;

interface UserSubscriptionRepositoryInterface
{
    public function save(UserSubscriptionInterface $userSubscription): void;

    public function delete(UserSubscriptionInterface $userSubscription): void;
}
