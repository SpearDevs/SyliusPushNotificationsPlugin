<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\Menu\Admin;

use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

final class AdminMenuListener
{
    public function addAdminMenuItems(MenuBuilderEvent $event): void
    {
        $menu = $event->getMenu();

        $newSubmenu = $menu
            ->addChild('speardevs_sylius_push_notification_plugin')
            ->setLabel('speardevs_sylius_push_notifications_plugin.ui.push_notifications');

        $newSubmenu
            ->addChild('send_push_notifications', [
                'route' => 'speardevs_send_push_notifications',
            ])
            ->setLabel('speardevs_sylius_push_notifications_plugin.ui.send_push_notification');

        $newSubmenu
            ->addChild('peardevs_sylius_push_notifications_plugin_admin_push_notification_template_index', [
                'route' => 'speardevs_sylius_push_notifications_plugin_admin_push_notification_template_index',
            ])
            ->setLabel('speardevs_sylius_push_notifications_plugin.ui.push_notification_templates');
    }
}
