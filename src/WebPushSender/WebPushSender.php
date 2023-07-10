<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\WebPushSender;

use BenTools\WebPushBundle\Model\Message\PushNotification;
use BenTools\WebPushBundle\Model\Subscription\UserSubscriptionManagerInterface;
use BenTools\WebPushBundle\Sender\PushMessageSender;
use SpearDevs\SyliusPushNotificationsPlugin\Entity\PushNotificationTemplate\PushNotificationTemplate;
use SpearDevs\SyliusPushNotificationsPlugin\Repository\UserSubscriptionRepositoryInterface;
use SpearDevs\SyliusPushNotificationsPlugin\WebPush\WebPush;
use SpearDevs\SyliusPushNotificationsPlugin\WebPush\WebPushInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\Component\User\Model\UserInterface;

final class WebPushSender implements WebPushSenderInterface
{
    public const PUSH_NEW_ORDER_CODE = "'push_new_order'";
    public const PUSH_ORDER_SHIPPED_CODE = "'push_order_shipped'";

    public function __construct(
        private UserSubscriptionRepositoryInterface $userSubscriptionRepository,
        private UserSubscriptionManagerInterface $userSubscriptionManager,
        private PushMessageSender $sender,
        private RepositoryInterface $pushNotificationTemplateRepository,
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

    public function sendOrderWebPush(OrderInterface $order, string $pushNotificationCode): void
    {
        /** @var PushNotificationTemplate $pushNotificationTemplate */
        $pushNotificationTemplate = $this->pushNotificationTemplateRepository->findOneBy(['code' => $pushNotificationCode]);

        /** @var UserInterface $user */
        $user = $order->getCustomer()->getUser();

        $webPush = new WebPush($order, $pushNotificationTemplate);

        $this->sendToUser(
            $webPush,
            $user->getEmail()
        );
    }
}
