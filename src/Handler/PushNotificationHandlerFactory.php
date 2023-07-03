<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\Handler;

use BenTools\WebPushBundle\Sender\PushMessageSender;
use SpearDevs\SyliusPushNotificationsPlugin\Factory\PushNotificationHistoryFactory;
use SpearDevs\SyliusPushNotificationsPlugin\Manager\UserSubscriptionManager;
use SpearDevs\SyliusPushNotificationsPlugin\Repository\MySQLUserSubscriptionRepository;
use SpearDevs\SyliusPushNotificationsPlugin\Repository\PushNotificationHistory\PushNotificationHistoryRepository;
use SpearDevs\SyliusPushNotificationsPlugin\Service\PushNotificationConfigurationService;

final class PushNotificationHandlerFactory
{
    public function __construct(
        private MySQLUserSubscriptionRepository $mySQLUserSubscriptionRepository,
        private UserSubscriptionManager $userSubscriptionManager,
        private PushMessageSender $sender,
        private PushNotificationHistoryFactory $pushNotificationHistoryFactory,
        private PushNotificationHistoryRepository $pushNotificationHistoryRepository,
        private PushNotificationConfigurationService $pushNotificationConfigurationService,
    ) {
    }

    private const INSTANCE_CLASS_TEMPLATE = 'SpearDevs\\SyliusPushNotificationsPlugin\\Handler\\%s';

    public function getPushNotificationHandler(string $receiverType): PushNotificationHandlerInterface
    {
        $class = sprintf(self::INSTANCE_CLASS_TEMPLATE, ucfirst($receiverType).'PushNotificationHandler');

        return new $class(
            $this->mySQLUserSubscriptionRepository,
            $this->userSubscriptionManager,
            $this->sender,
            $this->pushNotificationHistoryFactory,
            $this->pushNotificationHistoryRepository,
            $this->pushNotificationConfigurationService,
        );
    }
}
