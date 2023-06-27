<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\Handler;

use BenTools\WebPushBundle\Model\Message\PushNotification;
use BenTools\WebPushBundle\Sender\PushMessageSender;
use SpearDevs\SyliusPushNotificationsPlugin\Manager\UserSubscriptionManager;
use SpearDevs\SyliusPushNotificationsPlugin\Repository\MySQLUserSubscriptionRepository;
use SpearDevs\SyliusPushNotificationsPlugin\Repository\ShopUserRepository;
use Sylius\Component\Customer\Model\CustomerGroupInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\User\Model\User;
use Sylius\Component\User\Model\UserInterface;

final class GroupPushNotificationHandler extends PushNotificationHandler
{
    public function __construct(
        protected MySQLUserSubscriptionRepository $mySQLUserSubscriptionRepository,
        protected UserSubscriptionManager $userSubscriptionManager,
        protected PushMessageSender $sender,
    )
    {
        parent::__construct($mySQLUserSubscriptionRepository, $userSubscriptionManager, $sender);
    }

    public function sendToReceiver(string $pushTitle, string $pushContent, ?string $receiver = null): void
    {
        $subscriptions = ($receiver) ?
            $this->mySQLUserSubscriptionRepository->getSubscriptionsForUsersInGroup($receiver) :
            $this->mySQLUserSubscriptionRepository->getSubscriptionsForAllUsers();

        $this->send($subscriptions, $pushTitle, $pushContent);
    }
}
