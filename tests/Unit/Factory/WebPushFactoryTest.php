<?php

declare(strict_types=1);

namespace Tests\SpearDevs\SyliusPushNotificationsPlugin\Unit\Factory;

use PHPUnit\Framework\TestCase;
use SpearDevs\SyliusPushNotificationsPlugin\Entity\PushNotificationTemplate\PushNotificationTemplateInterface;
use SpearDevs\SyliusPushNotificationsPlugin\Factory\Interfaces\WebPushFactoryInterface;
use SpearDevs\SyliusPushNotificationsPlugin\Factory\WebPushFactory;
use SpearDevs\SyliusPushNotificationsPlugin\ParameterMapper\ParameterMapperInterface;
use SpearDevs\SyliusPushNotificationsPlugin\WebPush\WebPushInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

final class WebPushFactoryTest extends TestCase
{
    private WebPushFactoryInterface $webPushFactory;

    protected function setUp(): void
    {
        $this->webPushFactory = new WebPushFactory();
    }

    public function testCreateWithValidParameters()
    {
        //Given
        $parameterMapper = $this->createMock(ParameterMapperInterface::class);
        $resource = $this->createMock(ResourceInterface::class);
        $pushNotificationTemplate = $this->createMock(PushNotificationTemplateInterface::class);
        $customTitle = 'Custom Title';
        $customContent = 'Custom Content';

        $parameterMapper->expects($this->once())
            ->method('getTitle')
            ->with($resource, $pushNotificationTemplate, $customTitle)
            ->willReturn($customTitle);
        $parameterMapper->expects($this->once())
            ->method('getContent')
            ->with($resource, $pushNotificationTemplate, $customContent)
            ->willReturn($customContent);

        //When
        $webPush = $this->webPushFactory->create(
            $parameterMapper,
            $resource,
            $pushNotificationTemplate,
            $customTitle,
            $customContent,
        );

        //Then
        $this->assertInstanceOf(WebPushInterface::class, $webPush);
        $this->assertEquals($customTitle, $webPush->getTitle());
        $this->assertEquals($customContent, $webPush->getContent());
    }
}
