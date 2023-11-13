<?php

declare(strict_types=1);

namespace Tests\SpearDevs\SyliusPushNotificationsPlugin\Integration\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use SpearDevs\SyliusPushNotificationsPlugin\Entity\UserSubscription\UserSubscription;
use Sylius\Component\Core\Model\Channel;
use Sylius\Component\Core\Model\ShopUser;

final class RetailUserSubscriptionFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $shopUser = $this->getReference(RetailShopUserFixture::SHOP_USER_REFERENCE, ShopUser::class);
        $channel = $this->getReference(ChannelFixture::CHANNEL_REFERENCE, Channel::class);

        $userSubscription = new UserSubscription($shopUser, 'random_hash', []);
        $userSubscription->setCustomer($shopUser->getCustomer());
        $userSubscription->setChannel($channel);

        $manager->persist($userSubscription);
        $manager->flush();
    }
}
