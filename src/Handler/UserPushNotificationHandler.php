<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\Handler;

use BenTools\WebPushBundle\Sender\PushMessageSender;
use SpearDevs\SyliusPushNotificationsPlugin\Manager\UserSubscriptionManager;
use SpearDevs\SyliusPushNotificationsPlugin\Repository\ShopUserRepository;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\User\Model\User;
use Sylius\Component\User\Model\UserInterface;

final class UserPushNotificationHandler extends PushNotificationHandler
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
        /** @var UserInterface $receiver */
        $subscriptions = $this->userSubscriptionManager->findByUser($receiver);

        $this->send($subscriptions, $pushTitle, $pushContent);
    }
}
