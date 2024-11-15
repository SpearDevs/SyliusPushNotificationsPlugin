<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\Menu\Shop;

use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

final class AccountMenuListener
{
    public function addAccountMenuItems(MenuBuilderEvent $event): void
    {
        $menu = $event->getMenu();

        $menu
            ->addChild('push_notification_section', ['route' => 'speardevs_push_notifications'])
            ->setLabel('speardevs_sylius_push_notifications_plugin.ui.my_account.push_notifications')
            ->setLabelAttribute('icon', 'star');
    }
}
