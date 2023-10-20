<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\Service\WebPushHistoryCreator;

use BenTools\WebPushBundle\Model\Response\PushResponse;
use SpearDevs\SyliusPushNotificationsPlugin\Factory\PushNotificationHistoryFactoryInterface;
use SpearDevs\SyliusPushNotificationsPlugin\Repository\PushNotificationHistory\PushNotificationHistoryRepositoryInterface;
use SpearDevs\SyliusPushNotificationsPlugin\WebPush\WebPushInterface;

final class WebPushHistoryCreator implements WebPushHistoryCreatorInterface
{
    public function __construct(
        private PushNotificationHistoryFactoryInterface $pushNotificationHistoryFactory,
        private PushNotificationHistoryRepositoryInterface $pushNotificationHistoryRepository,
    ) {
    }

    public function create(WebPushInterface $webPush, PushResponse $pushResponse): void
    {
        $pushNotificationHistory =
            $this->pushNotificationHistoryFactory
                ->createNewWithPushNotificationData($webPush->getTitle(), $webPush->getContent(), $pushResponse);

        $this->pushNotificationHistoryRepository->save($pushNotificationHistory);
    }
}
