<?php

declare(strict_types=1);

namespace Tests\SpearDevs\SyliusPushNotificationsPlugin\Integration\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Sylius\Component\Core\Model\Customer;

final class CustomerFixture extends Fixture
{
    public const CUSTOMER_REFERENCE = 'customer';

    public const CUSTOMER_EMAIL = 'test@example.com';

    public const CUSTOMER_NAME = 'Test';

    public const CUSTOMER_LASTNAME = 'Tasty';

    public function load(ObjectManager $manager): void
    {
        $customer = new Customer();

        $customer->setEmail(self::CUSTOMER_EMAIL);
        $customer->setEmail(self::CUSTOMER_EMAIL);
        $customer->setFirstName(self::CUSTOMER_NAME);
        $customer->setLastName(self::CUSTOMER_LASTNAME);
        $manager->persist($customer);
        $manager->flush();

        $this->addReference(self::CUSTOMER_REFERENCE, $customer);
    }
}
