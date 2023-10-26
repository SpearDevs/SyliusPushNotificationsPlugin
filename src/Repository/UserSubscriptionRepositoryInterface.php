<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\Repository;

use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

interface UserSubscriptionRepositoryInterface extends RepositoryInterface
{
    public function getSubscriptionsForAllUsers(ChannelInterface $channel): iterable;

    public function getSubscriptionsForUsersInGroup(string $groupName, ChannelInterface $channel): iterable;

    public function getSubscriptionsForUserByEmail(string $email, ChannelInterface $channel): iterable;
}
