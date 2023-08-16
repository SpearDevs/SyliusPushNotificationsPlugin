<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\Factory\Interfaces;

use SpearDevs\SyliusPushNotificationsPlugin\Entity\PushNotificationTemplate\PushNotificationTemplateInterface;
use SpearDevs\SyliusPushNotificationsPlugin\ParameterMapper\ParameterMapperInterface;
use SpearDevs\SyliusPushNotificationsPlugin\WebPush\WebPush;
use Sylius\Component\Resource\Model\ResourceInterface;

interface WebPushFactoryInterface
{
    public function create(
        ParameterMapperInterface $parameterMapper,
        ?ResourceInterface $resource,
        ?PushNotificationTemplateInterface $pushNotificationTemplate,
        ?string $customTitle,
        ?string $customContent,
    ): WebPush;
}
