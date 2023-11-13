<?php

declare(strict_types=1);

namespace Tests\SpearDevs\SyliusPushNotificationsPlugin\Integration\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use SpearDevs\SyliusPushNotificationsPlugin\Entity\PushNotificationHistory\PushNotificationHistory;
use Sylius\Component\Core\Model\Channel;
use Sylius\Component\Core\Model\ShopUser;

final class PushNotificationReceivedHistoryFixture extends Fixture
{
    public const PUSH_TITLE = 'Test';

    public const PUSH_CONTENT = 'Test';

    public const PUSH_RESPONSE_STATUS_CODE = 201;

    public const PUSH_HISTORY_RESPONSE_STATE = 'received';

    public function load(ObjectManager $manager): void
    {
        $pushNotificationHistory = new PushNotificationHistory();
        $pushNotificationHistory->setTitle(self::PUSH_TITLE);
        $pushNotificationHistory->setContent(self::PUSH_CONTENT);
        $pushNotificationHistory->setResponseStatusCode(self::PUSH_RESPONSE_STATUS_CODE);
        $pushNotificationHistory->setUser(
            $this->getReference(ShopUserFixture::SHOP_USER_REFERENCE, ShopUser::class),
        );

        $pushNotificationHistory->setChannel(
            $this->getReference(ChannelFixture::CHANNEL_REFERENCE, Channel::class),
        );

        $pushNotificationHistory->setState(self::PUSH_HISTORY_RESPONSE_STATE);

        $manager->persist($pushNotificationHistory);
        $manager->flush();
    }
}
