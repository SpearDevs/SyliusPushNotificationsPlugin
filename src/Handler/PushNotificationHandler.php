<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\Handler;

use BenTools\WebPushBundle\Model\Message\PushNotification;
use BenTools\WebPushBundle\Model\Subscription\UserSubscriptionManagerInterface;
use BenTools\WebPushBundle\Sender\PushMessageSender;
use SpearDevs\SyliusPushNotificationsPlugin\Repository\UserSubscriptionRepositoryInterface;
use SpearDevs\SyliusPushNotificationsPlugin\WebPush\WebPushInterface;

final class PushNotificationHandler implements PushNotificationHandlerInterface
{
    public function __construct(
        protected UserSubscriptionRepositoryInterface $userSubscriptionRepository,
        protected UserSubscriptionManagerInterface $userSubscriptionManager,
        protected PushMessageSender $sender,
    ) {
    }

    public function sendToGroup(WebPushInterface $webPush, ?string $receiver = null): void
    {
        $subscriptions = ($receiver) ?
            $this->userSubscriptionRepository->getSubscriptionsForUsersInGroup($receiver) :
            $this->userSubscriptionRepository->getSubscriptionsForAllUsers();

        $this->send($webPush, $subscriptions);
    }

    public function sendToUser(WebPushInterface $webPush, ?string $receiver = null): void
    {
        $subscriptions = $this->userSubscriptionRepository->getSubscriptionsForUserByEmail($receiver);

        $this->send($webPush, $subscriptions);
    }

    private function send(WebPushInterface $webPush, iterable $subscriptions): void
    {
        $notification = new PushNotification($webPush->getTitle(), [
            PushNotification::BODY => $webPush->getContent(),
        ]);

        $responses = $this->sender->push($notification->createMessage(), $subscriptions);

        foreach ($responses as $response) {
            if ($response->isExpired()) {
                $this->userSubscriptionManager->delete($response->getSubscription());
            }
        }
    }
}
