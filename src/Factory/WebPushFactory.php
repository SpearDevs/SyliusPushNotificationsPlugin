<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\Factory;

use SpearDevs\SyliusPushNotificationsPlugin\Entity\PushNotificationTemplate\PushNotificationTemplateInterface;
use SpearDevs\SyliusPushNotificationsPlugin\Factory\Interfaces\WebPushFactoryInterface;
use SpearDevs\SyliusPushNotificationsPlugin\ParameterMapper\ParameterMapperInterface;
use SpearDevs\SyliusPushNotificationsPlugin\WebPush\WebPush;
use Sylius\Component\Resource\Model\ResourceInterface;

final class WebPushFactory implements WebPushFactoryInterface
{
    public function create(
        ParameterMapperInterface $parameterMapper,
        ?ResourceInterface $resource,
        ?PushNotificationTemplateInterface $pushNotificationTemplate,
        ?string $customTitle = null,
        ?string $customContent = null,
    ): WebPush {
        $pushTitle = $parameterMapper->getTitle($resource, $pushNotificationTemplate, $customTitle);
        $pushContent = $parameterMapper->getContent($resource, $pushNotificationTemplate, $customContent);

        return new WebPush($pushTitle, $pushContent);
    }
}
