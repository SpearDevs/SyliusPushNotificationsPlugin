<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\ParameterMapper;

use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Webmozart\Assert\Assert;

class OrderParameterMapper extends AbstractParameterMapper
{
    private array $orderData = [];

    public function mapParameters(ResourceInterface $resource, string $text): string
    {
        Assert::isInstanceOf(
            $resource,
            OrderInterface::class,
            'Mapper can be used with an entity that implements the Sylius\Component\Core\Model\OrderInterface',
        );

        /** @var OrderInterface $resource */
        $orderData = $this->getOrderData($resource);

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
