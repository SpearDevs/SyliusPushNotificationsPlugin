<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\Factory;

use SpearDevs\SyliusPushNotificationsPlugin\Entity\PushNotificationTemplate\PushNotificationTemplateInterface;
use SpearDevs\SyliusPushNotificationsPlugin\Factory\Interfaces\WebPushFactoryInterface;
use SpearDevs\SyliusPushNotificationsPlugin\OrderParameterMapper\OrderParameterMapper;
use SpearDevs\SyliusPushNotificationsPlugin\WebPush\WebPush;
use Sylius\Component\Core\Model\OrderInterface;

final class WebPushFactory implements WebPushFactoryInterface
{
    public function __construct(
        private OrderParameterMapper $parameterMapper,
    ) {
    }

    public function create(
        ?OrderInterface $order,
        ?PushNotificationTemplateInterface $pushNotificationTemplate,
        ?string $customTitle = null,
        ?string $customContent = null,
    ): WebPush {
        $pushTitle = $this->parameterMapper->getTitle($order, $pushNotificationTemplate, $customTitle);
        $pushContent = $this->parameterMapper->getContent($order, $pushNotificationTemplate, $customContent);

        return new WebPush($pushTitle, $pushContent);
    }
}
