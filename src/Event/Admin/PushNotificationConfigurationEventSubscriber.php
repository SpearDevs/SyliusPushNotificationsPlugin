<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\Event\Admin;

use SpearDevs\SyliusPushNotificationsPlugin\Entity\PushNotificationConfiguration\PushNotificationConfigurationInterface;
use SpearDevs\SyliusPushNotificationsPlugin\Uploader\PushNotificationIconUploaderInterface;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class PushNotificationConfigurationEventSubscriber implements EventSubscriberInterface
{
    public function __construct(private PushNotificationIconUploaderInterface $uploader) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'speardevs_sylius_push_notifications_plugin.push_notification_configuration.pre_update' =>
                'prePushNotificationConfigurationUpdate',
        ];
    }

    public function prePushNotificationConfigurationUpdate(ResourceControllerEvent $event): void
    {
        $pushNotificationConfiguration = $event->getSubject();

        if (!$pushNotificationConfiguration instanceof PushNotificationConfigurationInterface) {
            return;
        }

        $this->uploader->upload($pushNotificationConfiguration);
    }
}
