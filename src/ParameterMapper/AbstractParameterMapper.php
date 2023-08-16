<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\ParameterMapper;

use SpearDevs\SyliusPushNotificationsPlugin\Entity\PushNotificationTemplate\PushNotificationTemplateInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

abstract class AbstractParameterMapper implements ParameterMapperInterface
{
    public function getTitle(
        ?ResourceInterface $resource,
        ?PushNotificationTemplateInterface $pushNotificationTemplate,
        ?string $customTitle,
    ): string {
        if ($resource !== null && $pushNotificationTemplate !== null) {
            return $this->mapParameters($resource, $pushNotificationTemplate->getTitle());
        }

        if ($customTitle !== null) {
            return $customTitle;
        }

        if ($pushNotificationTemplate !== null) {
            return $pushNotificationTemplate->getTitle();
        }

        return '';
    }

    public function getContent(
        ?ResourceInterface $resource,
        ?PushNotificationTemplateInterface $pushNotificationTemplate,
        ?string $customContent,
    ): string {
        if ($resource !== null && $pushNotificationTemplate !== null) {
            return $this->mapParameters($resource, $pushNotificationTemplate->getContent());
        }

        if ($customContent !== null) {
            return $customContent;
        }

        if ($pushNotificationTemplate !== null) {
            return $pushNotificationTemplate->getContent();
        }

        return '';
    }
}
