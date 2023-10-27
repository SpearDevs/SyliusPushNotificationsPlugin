<?php

declare(strict_types=1);

namespace Tests\SpearDevs\SyliusPushNotificationsPlugin\Integration\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Sylius\Component\Core\Model\Customer;
use Sylius\Component\Core\Model\ShopUser;

final class ShopUserFixture extends Fixture
{
    public const SHOP_USER_REFERENCE = 'shop_user';

    public function load(ObjectManager $manager): void
    {
        $shopUser = new ShopUser();

        $shopUser->setCustomer(
            $this->getReference(CustomerFixture::CUSTOMER_REFERENCE, Customer::class),
        );

        $manager->persist($shopUser);
        $manager->flush();

        $this->addReference(self::SHOP_USER_REFERENCE, $shopUser);
    }
}
