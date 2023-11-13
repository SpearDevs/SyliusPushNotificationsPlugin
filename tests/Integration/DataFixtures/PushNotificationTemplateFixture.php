<?php

declare(strict_types=1);

namespace Tests\SpearDevs\SyliusPushNotificationsPlugin\Integration\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use SpearDevs\SyliusPushNotificationsPlugin\Entity\PushNotificationTemplate\PushNotificationTemplate;

final class PushNotificationTemplateFixture extends Fixture
{
    public const PUSH_ORDER_SHIPPED_CODE = 'push_order_shipped';

    public const PUSH_NEW_ORDER_CODE = 'push_new_order';

    public function load(ObjectManager $manager): void
    {
        $pushNotificationTemplates =
            [
                'template_order_shipped' => [
                    'title' => 'Order number {order_id} has been shipped',
                    'content' => '{customer_name}, your order has been shipped',
                    'code' => self::PUSH_ORDER_SHIPPED_CODE,
                ],
                'template_order_new' => [
                    'title' => 'New order number {order_id}',
                    'content' => 'A new order has just been created',
                    'code' => self::PUSH_NEW_ORDER_CODE,
                ],
            ];

        foreach ($pushNotificationTemplates as $pushNotificationTemplateData) {
            $pushNotificationTemplate = new PushNotificationTemplate();
            $pushNotificationTemplate->setTitle($pushNotificationTemplateData['title']);
            $pushNotificationTemplate->setContent($pushNotificationTemplateData['content']);
            $pushNotificationTemplate->setCode($pushNotificationTemplateData['code']);

            $manager->persist($pushNotificationTemplate);
        }

        $manager->flush();
    }
}
