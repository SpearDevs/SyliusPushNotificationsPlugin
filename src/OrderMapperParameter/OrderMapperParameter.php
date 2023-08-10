<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\OrderMapperParameter;

use SpearDevs\SyliusPushNotificationsPlugin\Entity\PushNotificationTemplate\PushNotificationTemplateInterface;
use Sylius\Component\Core\Model\OrderInterface;

class OrderMapperParameter
{
    private array $orderData = [];

    public function getTitle(
        ?OrderInterface $order,
        ?PushNotificationTemplateInterface $pushNotificationTemplate,
        ?string $customTitle,
    ): string {
        if ($order !== null && $pushNotificationTemplate !== null) {
            return $this->mapParameters($order, $pushNotificationTemplate->getTitle());
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
        ?OrderInterface $order,
        ?PushNotificationTemplateInterface $pushNotificationTemplate,
        ?string $customContent,
    ): string {
        if ($order !== null && $pushNotificationTemplate !== null) {
            return $this->mapParameters($order, $pushNotificationTemplate->getContent());
        }

        if ($customContent !== null) {
            return $customContent;
        }

        if ($pushNotificationTemplate !== null) {
            return $pushNotificationTemplate->getContent();
        }

        return '';
    }

    public function mapParameters(OrderInterface $order, string $text): string
    {
        $orderData = $this->getOrderData($order);

        $change = [
            '{order_id}' => $orderData['number'],
            '{customer_name}' => $orderData['customer_name'],
        ];

        return strtr($text, $change);
    }

    private function getOrderData(OrderInterface $order): array
    {
        if (!count($this->orderData) === 0) {
            return $this->orderData;
        }

        $this->orderData = [
            'number' => $order->getNumber(),
            'customer_name' => $order->getCustomer()?->getFirstName(),
        ];

        return $this->orderData;
    }
}
