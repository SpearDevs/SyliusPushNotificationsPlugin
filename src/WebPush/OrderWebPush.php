<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\WebPush;

use SpearDevs\SyliusPushNotificationsPlugin\Entity\PushNotificationTemplate;
use SpearDevs\SyliusPushNotificationsPlugin\Handler\UserPushNotificationHandler;
use SpearDevs\SyliusPushNotificationsPlugin\Service\OrderMapperParameter;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\Component\User\Model\UserInterface;

final class OrderWebPush
{
    public function __construct(
        private UserPushNotificationHandler $pushNotificationHandler,
        private RepositoryInterface $pushNotificationTemplateRepository,
        private OrderMapperParameter $mapperParameter,
    ) {
    }

    public function sendWebPush(OrderInterface $order, string $pushNotificationCode): void
    {
        /** @var PushNotificationTemplate $pushNotificationTemplate */
        $pushNotificationTemplate = $this->pushNotificationTemplateRepository->findOneBy(['code' => $pushNotificationCode]);

        /** @var UserInterface $user */
        $user = $order->getCustomer()->getUser();

        $this->pushNotificationHandler->sendToReceiver(
            $this->mapperParameter->getContent($order, $pushNotificationTemplate),
            $this->mapperParameter->getTitle($order, $pushNotificationTemplate),
            $user
        );
    }
}
