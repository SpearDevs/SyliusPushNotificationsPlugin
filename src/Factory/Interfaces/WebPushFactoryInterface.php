<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\Factory\Interfaces;

use SpearDevs\SyliusPushNotificationsPlugin\Entity\PushNotificationTemplate\PushNotificationTemplateInterface;
use SpearDevs\SyliusPushNotificationsPlugin\WebPush\WebPush;
use Sylius\Component\Core\Model\OrderInterface;

interface WebPushFactoryInterface
{
    public function create(
        ?OrderInterface $order,
        ?PushNotificationTemplateInterface $pushNotificationTemplate,
        ?string $customTitle,
        ?string $customContent,
    ): WebPush;
}
