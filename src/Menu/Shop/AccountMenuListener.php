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
            ->addChild('new', ['route' => 'spear_devs_push_notifications_section'])
            ->setLabel('sylius.ui.my_account.push_notifications')
            ->setLabelAttribute('icon', 'star')
        ;
    }
}
