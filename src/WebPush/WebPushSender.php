<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\WebPush;

use SpearDevs\SyliusPushNotificationsPlugin\Entity\PushNotificationTemplate\PushNotificationTemplate;
use SpearDevs\SyliusPushNotificationsPlugin\Handler\PushNotificationHandlerInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\Component\User\Model\UserInterface;

final class WebPushSender
{
    public const PUSH_NEW_ORDER_CODE = "'push_new_order'";
    public const PUSH_ORDER_SEND_CODE = "'push_order_send'";

    public function __construct(
        private PushNotificationHandlerInterface $pushNotificationHandler,
        private RepositoryInterface $pushNotificationTemplateRepository,
    ) {
    }

    public function sendWebPush(OrderInterface $order, string $pushNotificationCode): void
    {
        /** @var PushNotificationTemplate $pushNotificationTemplate */
        $pushNotificationTemplate = $this->pushNotificationTemplateRepository->findOneBy(['code' => $pushNotificationCode]);

        /** @var UserInterface $user */
        $user = $order->getCustomer()->getUser();

        $webPush = new WebPush($order, $pushNotificationTemplate);

        $this->pushNotificationHandler->sendToUser(
            $webPush,
            $user->getEmail()
        );
    }
}
