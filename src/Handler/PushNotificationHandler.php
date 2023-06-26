<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\Handler;

use BenTools\WebPushBundle\Model\Message\PushNotification;
use BenTools\WebPushBundle\Sender\PushMessageSender;
use SpearDevs\SyliusPushNotificationsPlugin\Manager\UserSubscriptionManager;
use SpearDevs\SyliusPushNotificationsPlugin\Repository\ShopUserRepository;
use Sylius\Component\User\Model\User;

final class PushNotificationHandler
{
    public function __construct(
        private ShopUserRepository $shopUserRepository,
        private UserSubscriptionManager $userSubscriptionManager,
        private PushMessageSender $sender,
    ) {
    }

    public function sendToUsers(string $pushTitle, string $pushContent, ?string $customerGroup = null): void
    {
        $users = ($customerGroup) ?
            $this->shopUserRepository->findUsersWithSubscriptionByGroup($customerGroup) :
            $this->shopUserRepository->findAllUsersWithSubscription();

        $this->sendPushNotificationForUsers($users, $pushTitle, $pushContent);
    }

    private function sendPushNotificationForUsers(iterable $users, string $pushTitle, string $pushContent): void
    {
        /** @var User $user */
        foreach ($users as $user) {
            $subscriptions = $this->userSubscriptionManager->findByUser($user);

            if ([] === $subscriptions) {
                continue;
            }

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
}
