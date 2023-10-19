<?php

declare(strict_types=1);

namespace Tests\SpearDevs\SyliusPushNotificationsPlugin\Unit\Event\Admin;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SpearDevs\SyliusPushNotificationsPlugin\Event\Admin\PushNotificationConfigurationEventSubscriber;
use SpearDevs\SyliusPushNotificationsPlugin\Uploader\PushNotificationIconUploaderInterface;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\Component\Core\Model\CustomerInterface;

final class PushNotificationConfigurationEventSubscriberTest extends TestCase
{
    /** @var PushNotificationIconUploaderInterface&MockObject */
    private PushNotificationIconUploaderInterface $pushNotificationIconUploader;

    /** @var PushNotificationConfigurationEventSubscriber&MockObject */
    private PushNotificationConfigurationEventSubscriber $pushNotificationConfigurationEventSubscriber;

    protected function setUp(): void
    {
        $this->pushNotificationIconUploader = $this->createMock(PushNotificationIconUploaderInterface::class);
        $this->pushNotificationConfigurationEventSubscriber = new PushNotificationConfigurationEventSubscriber(
            $this->pushNotificationIconUploader,
        );
    }

    public function testItDoesNothingIfEventIsNotRelatedWithPushNotificationConfiguration(): void
    {
        //Given
        $customer = $this->createMock(CustomerInterface::class);

        $event = $this->createMock(ResourceControllerEvent::class);
        $event->method('getSubject')
            ->willReturn($customer);

        //Then
        $this->pushNotificationIconUploader
            ->expects($this->never())
            ->method('upload')
            ->with($customer);

        //When
        $this->pushNotificationConfigurationEventSubscriber->prePushNotificationConfigurationUpdate($event);
    }
}
