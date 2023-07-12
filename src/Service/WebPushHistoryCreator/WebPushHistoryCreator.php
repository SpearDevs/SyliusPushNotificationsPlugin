<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\Service\WebPushHistoryCreator;

use SpearDevs\SyliusPushNotificationsPlugin\Factory\PushNotificationHistoryFactory;
use SpearDevs\SyliusPushNotificationsPlugin\Repository\PushNotificationHistory\PushNotificationHistoryRepositoryInterface;
use SpearDevs\SyliusPushNotificationsPlugin\WebPush\WebPushInterface;

final class WebPushHistoryCreator implements WebPushHistoryCreatorInterface
{
    public function __construct(
        private PushNotificationHistoryFactory $pushNotificationHistoryFactory,
        private PushNotificationHistoryRepositoryInterface $pushNotificationHistoryRepository,
    ) {
    }

    public function create(WebPushInterface $webPush, array $subscriptionsArray): void
    {
        foreach ($subscriptionsArray as $subscription) {
            $pushNotificationHistory =
                $this->pushNotificationHistoryFactory
                    ->createNewWithPushNotificationData($webPush->getTitle(), $webPush->getContent(), $subscription);

            $this->pushNotificationHistoryRepository->save($pushNotificationHistory);
        }
    }
}
