<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\OrderParameterMapper;

use SpearDevs\SyliusPushNotificationsPlugin\Entity\PushNotificationTemplate\PushNotificationTemplateInterface;
use Sylius\Component\Core\Model\OrderInterface;

interface OrderParameterMapperInterface
{
    public function getTitle(?OrderInterface $order, ?PushNotificationTemplateInterface $pushNotificationTemplate, ?string $customTitle): string;

    public function getContent(?OrderInterface $order, ?PushNotificationTemplateInterface $pushNotificationTemplate, ?string $customContent): string;

    public function mapParameters(OrderInterface $order, string $text): string;
}
