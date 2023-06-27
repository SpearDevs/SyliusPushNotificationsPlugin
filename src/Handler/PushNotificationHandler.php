<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\Handler;

use BenTools\WebPushBundle\Model\Message\PushNotification;
use BenTools\WebPushBundle\Sender\PushMessageSender;
use SpearDevs\SyliusPushNotificationsPlugin\Manager\UserSubscriptionManager;
use SpearDevs\SyliusPushNotificationsPlugin\Repository\MySQLUserSubscriptionRepository;

final class PushNotificationHandler
{
    public function __construct(
        private UserSubscriptionManager $userSubscriptionManager,
        private PushMessageSender $sender,
        private MySQLUserSubscriptionRepository $mySQLUserSubscriptionRepository,
    ) {
    }

    public function sendToUsers(string $pushTitle, string $pushContent, ?string $customerGroup = null): void
    {
        $subscriptions = ($customerGroup) ?
            $this->mySQLUserSubscriptionRepository->getSubscriptionsForUsersInGroup($customerGroup) :
            $this->mySQLUserSubscriptionRepository->getSubscriptionsForAllUsers();

        $this->sendPushNotificationForUsers($subscriptions, $pushTitle, $pushContent);
    }

    private function sendPushNotificationForUsers(iterable $subscriptions, string $pushTitle, string $pushContent): void
    {
        $notification = new PushNotification($pushTitle, [
            PushNotification::BODY => $pushContent,
        ]);

        $responses = $this->sender->push($notification->createMessage(), $subscriptions);

        foreach ($responses as $response) {
            if ($response->isExpired()) {
                $this->userSubscriptionManager->delete($response->getSubscription());
            }
        }
    }
}
