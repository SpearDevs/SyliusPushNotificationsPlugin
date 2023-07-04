<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\Handler;

use BenTools\WebPushBundle\Model\Message\PushNotification;
use BenTools\WebPushBundle\Model\Subscription\UserSubscriptionManagerInterface;
use BenTools\WebPushBundle\Sender\PushMessageSender;
use SpearDevs\SyliusPushNotificationsPlugin\Repository\UserSubscriptionRepositoryInterface;

final class PushNotificationHandler implements PushNotificationHandlerInterface
{
    public function __construct(
        protected UserSubscriptionRepositoryInterface $userSubscriptionRepository,
        protected UserSubscriptionManagerInterface $userSubscriptionManager,
        protected PushMessageSender $sender,
    ) {
    }

    public function sendToGroup(string $pushTitle, string $pushContent, ?string $receiver = null): void
    {
        $subscriptions = ($receiver) ?
            $this->userSubscriptionRepository->getSubscriptionsForUsersInGroup($receiver) :
            $this->userSubscriptionRepository->getSubscriptionsForAllUsers();

        $this->send($subscriptions, $pushTitle, $pushContent);
    }

    public function sendToUser(string $pushTitle, string $pushContent, ?string $receiver = null): void
    {
        $subscriptions = $this->userSubscriptionRepository->getSubscriptionsForUserByEmail($receiver);

        $this->send($subscriptions, $pushTitle, $pushContent);
    }

    private function send(iterable $subscriptions, string $pushTitle, string $pushContent): void
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
