<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\Handler;

use BenTools\WebPushBundle\Model\Message\PushNotification;
use BenTools\WebPushBundle\Sender\PushMessageSender;
use SpearDevs\SyliusPushNotificationsPlugin\Manager\UserSubscriptionManager;
use SpearDevs\SyliusPushNotificationsPlugin\Repository\ShopUserRepository;
use Sylius\Component\Customer\Model\CustomerGroupInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\User\Model\User;
use Sylius\Component\User\Model\UserInterface;

final class GroupPushNotificationHandler extends PushNotificationHandler
{
    public function __construct(
        protected ShopUserRepository $shopUserRepository,
        protected UserSubscriptionManager $userSubscriptionManager,
        protected PushMessageSender $sender,
    )
    {
        parent::__construct($shopUserRepository, $userSubscriptionManager, $sender);
    }

    public function sendToReceiver(string $pushTitle, string $pushContent, ?ResourceInterface $receiver = null): void
    {
        /** @var ?CustomerGroupInterface $receiver */
        $users = $this->shopUserRepository->findUsersByGroup($receiver);

        /** @var User $user */
        foreach ($users as $user) {
            $subscriptions = $this->userSubscriptionManager->findByUser($user);
            $this->send($subscriptions, $pushTitle, $pushContent);
        }
    }
}
