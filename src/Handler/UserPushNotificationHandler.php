<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\Handler;

use BenTools\WebPushBundle\Sender\PushMessageSender;
use SpearDevs\SyliusPushNotificationsPlugin\Factory\PushNotificationHistoryFactory;
use SpearDevs\SyliusPushNotificationsPlugin\Manager\UserSubscriptionManager;
use SpearDevs\SyliusPushNotificationsPlugin\Repository\MySQLUserSubscriptionRepository;
use SpearDevs\SyliusPushNotificationsPlugin\Repository\PushNotificationHistory\PushNotificationHistoryRepository;
use SpearDevs\SyliusPushNotificationsPlugin\Service\PushNotificationConfigurationService;

final class UserPushNotificationHandler extends PushNotificationHandler
{
    public function __construct(
        protected MySQLUserSubscriptionRepository $mySQLUserSubscriptionRepository,
        protected UserSubscriptionManager $userSubscriptionManager,
        protected PushMessageSender $sender,
        protected PushNotificationHistoryFactory $pushNotificationHistoryFactory,
        protected PushNotificationHistoryRepository $pushNotificationHistoryRepository,
        protected PushNotificationConfigurationService $pushNotificationConfigurationService,
    ) {
        parent::__construct(
            $mySQLUserSubscriptionRepository,
            $userSubscriptionManager,
            $sender,
            $pushNotificationHistoryFactory,
            $pushNotificationHistoryRepository,
            $pushNotificationConfigurationService,
        );
    }

    public function sendToReceiver(string $pushTitle, string $pushContent, ?string $receiver = null): void
    {
        $subscriptions = $this->mySQLUserSubscriptionRepository->getSubscriptionsForUserByEmail($receiver);

        $this->send($subscriptions, $pushTitle, $pushContent);
    }
}
