<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\Handler;

use BenTools\WebPushBundle\Model\Message\PushNotification;
use BenTools\WebPushBundle\Sender\PushMessageSender;
use SpearDevs\SyliusPushNotificationsPlugin\Manager\UserSubscriptionManager;
use SpearDevs\SyliusPushNotificationsPlugin\Repository\MySQLUserSubscriptionRepository;
use Sylius\Component\Resource\Model\ResourceInterface;

abstract class PushNotificationHandler implements PushNotificationHandlerInterface
{
    public function __construct(
        protected MySQLUserSubscriptionRepository $mySQLUserSubscriptionRepository,
        protected UserSubscriptionManager $userSubscriptionManager,
        protected PushMessageSender $sender,
    ) {
    }

    abstract public function sendToReceiver(string $pushTitle, string $pushContent, ?ResourceInterface $receiver = null): void;

    protected function send(iterable $subscriptions, string $pushTitle, string $pushContent): void
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
