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
            ->addChild('new')
            ->setLabel('speardevs_sylius_push_notification_plugin.ui.push_notifications');

        $newSubmenu
            ->addChild('section', [
                'route' => 'speardevs_send_push_notifications',
            ])
            ->setLabel('speardevs_sylius_push_notification_plugin.ui.send_push_notification');
    }
}
