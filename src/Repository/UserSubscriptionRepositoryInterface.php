<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\Repository;

interface UserSubscriptionRepositoryInterface
{
    public function getSubscriptionsForAllUsers(): iterable;

    public function getSubscriptionsForUsersInGroup(string $groupName): iterable;

    public function getSubscriptionsForUserByEmail(string $email): iterable;
}
