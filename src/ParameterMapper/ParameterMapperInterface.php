<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\ParameterMapper;

use SpearDevs\SyliusPushNotificationsPlugin\Entity\PushNotificationTemplate\PushNotificationTemplateInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

interface ParameterMapperInterface
{
    public function getTitle(?ResourceInterface $resource, ?PushNotificationTemplateInterface $pushNotificationTemplate, ?string $customTitle): string;

    public function getContent(?ResourceInterface $resource, ?PushNotificationTemplateInterface $pushNotificationTemplate, ?string $customContent): string;

    public function mapParameters(ResourceInterface $resource, string $text): string;
}
