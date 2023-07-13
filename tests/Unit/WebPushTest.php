<?php

declare(strict_types=1);

namespace Tests\SpearDevs\SyliusPushNotificationsPlugin\Unit;

use PHPUnit\Framework\TestCase;
use SpearDevs\SyliusPushNotificationsPlugin\Entity\PushNotificationTemplate\PushNotificationTemplateInterface;
use Sylius\Component\Core\Model\OrderInterface;
use SpearDevs\SyliusPushNotificationsPlugin\WebPush\WebPush;
use SpearDevs\SyliusPushNotificationsPlugin\WebPush\WebPushException;

class WebPushTest extends TestCase
{
    public function testCreateWebPushWithOrderAndPushNotificationTemplate(): void
    {
        $order = $this->createMock(OrderInterface::class);
        $pushNotificationTemplate = $this->createMock(PushNotificationTemplateInterface::class);
        $customTitle = null;
        $customContent = null;

        $webPush = new WebPush($order, $pushNotificationTemplate, $customTitle, $customContent);

        self::assertInstanceOf(WebPush::class, $webPush);
    }

    public function testCreateWebPushWithCustomTitleAndContent(): void
    {
        $order = null;
        $pushNotificationTemplate = null;
        $customTitle = 'Custom Title';
        $customContent = 'Custom Content';

        $webPush = new WebPush($order, $pushNotificationTemplate, $customTitle, $customContent);

        self::assertInstanceOf(WebPush::class, $webPush);
    }

    public function testCreateWebPushWithOrderAndNullPushNotificationTemplate(): void
    {
        $this->expectException(WebPushException::class);
        $this->expectExceptionMessage('Push notification template can not be null');

        $order = $this->createMock(OrderInterface::class);
        $pushNotificationTemplate = null;
        $customTitle = null;
        $customContent = null;

        new WebPush($order, $pushNotificationTemplate, $customTitle, $customContent);
    }

    public function testCreateWebPushWithNullOrderAndNullCustomTitleAndContent(): void
    {
        $this->expectException(WebPushException::class);
        $this->expectExceptionMessage('Custom push notification title and content can not be null');

        $order = null;
        $pushNotificationTemplate = null;
        $customTitle = null;
        $customContent = null;

        new WebPush($order, $pushNotificationTemplate, $customTitle, $customContent);
    }
}
