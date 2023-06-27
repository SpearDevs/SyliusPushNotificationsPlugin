<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\Handler;

use BenTools\WebPushBundle\Sender\PushMessageSender;
use SpearDevs\SyliusPushNotificationsPlugin\Manager\UserSubscriptionManager;
use SpearDevs\SyliusPushNotificationsPlugin\Repository\MySQLUserSubscriptionRepository;

final class UserPushNotificationHandler extends PushNotificationHandler
{
    public function __construct(
        protected MySQLUserSubscriptionRepository $mySQLUserSubscriptionRepository,
        protected UserSubscriptionManager $userSubscriptionManager,
        protected PushMessageSender $sender,
    ) {
        parent::__construct($mySQLUserSubscriptionRepository, $userSubscriptionManager, $sender);
    }

    public function sendToReceiver(string $pushTitle, string $pushContent, ?string $receiver = null): void
    {
        $subscriptions = $this->mySQLUserSubscriptionRepository->getSubscriptionsForUserByEmail($receiver);

        $this->send($subscriptions, $pushTitle, $pushContent);
    }
}
