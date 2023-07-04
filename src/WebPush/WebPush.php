<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\WebPush;

use SpearDevs\SyliusPushNotificationsPlugin\Entity\PushNotificationTemplate\PushNotificationTemplateInterface;
use Sylius\Component\Core\Model\OrderInterface;

class WebPush implements WebPushInterface
{
    private array $orderData = [];

    public function __construct(
        private ?OrderInterface $order,
        private ?PushNotificationTemplateInterface $pushNotificationTemplate,
        private ?string $customTitle = null,
        private ?string $customContent = null,
    ) {
    }

    public function getTitle(): string
    {
        if ($this->order) {
            return $this->mapParameters($this->order, $this->pushNotificationTemplate->getTitle());
        }

        if ($this->customTitle) {
            return $this->customTitle;
        }

        return $this->pushNotificationTemplate->getTitle();
    }

    public function getContent(): string
    {
        if ($this->order) {
            return $this->mapParameters($this->order, $this->pushNotificationTemplate->getContent());
        }

        if ($this->customContent) {
            return $this->customContent;
        }

        return $this->pushNotificationTemplate->getContent();
    }

    private function mapParameters(?OrderInterface $order, string $text)
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
            'customer_name' => $order->getCustomer()->getFirstName(),
        ];

        return $this->orderData;
    }
}
