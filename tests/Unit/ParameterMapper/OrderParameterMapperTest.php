<?php

declare(strict_types=1);

namespace Tests\SpearDevs\SyliusPushNotificationsPlugin\Unit\ParameterMapper;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SpearDevs\SyliusPushNotificationsPlugin\ParameterMapper\OrderParameterMapper;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Model\OrderInterface;

final class OrderParameterMapperTest extends TestCase
{
    /** @var OrderParameterMapper&MockObject */
    private OrderParameterMapper $orderParameterMapper;

    protected function setUp(): void
    {
        $this->orderParameterMapper = new OrderParameterMapper();
    }

    public function testMapParametersWithValidOrder()
    {
        //Given
        $text = 'Order #{order_id} for {customer_name}';

        $expectedResult = 'Order #12345 for John Doe';

        $orderData = [
            'number' => '12345',
            'customer_name' => 'John Doe',
        ];

        $order = $this->createMock(OrderInterface::class);
        $customer = $this->createMock(CustomerInterface::class);

        $order->expects($this->once())
            ->method('getNumber')
            ->willReturn($orderData['number']);

        $order->expects($this->once())
            ->method('getCustomer')
            ->willReturn($customer);

        $customer->expects($this->once())
            ->method('getFirstName')
            ->willReturn($orderData['customer_name']);

        //When
        $result = $this->orderParameterMapper->mapParameters($order, $text);

        //Then
        $this->assertEquals($expectedResult, $result);
    }

    public function testMapParametersWithInvalidResource()
    {
        //Given
        $resource = $this->createMock(CustomerInterface::class);
        $text = 'Order #{order_id} for {customer_name}';

        //Then
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Mapper can be used with an entity that implements the Sylius\Component\Core\Model\OrderInterface');

        //When
        $this->orderParameterMapper->mapParameters($resource, $text);
    }
}
