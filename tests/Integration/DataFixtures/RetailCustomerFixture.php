<?php

declare(strict_types=1);

namespace Tests\SpearDevs\SyliusPushNotificationsPlugin\Integration\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Sylius\Component\Core\Model\Customer;
use Sylius\Component\Customer\Model\CustomerGroup;

final class RetailCustomerFixture extends Fixture
{
    public const CUSTOMER_REFERENCE = 'retail_customer';

    public const CUSTOMER_EMAIL = 'test@example.com';

    public function load(ObjectManager $manager): void
    {
        $customer = new Customer();

        $customer->setEmail(self::CUSTOMER_EMAIL);
        $customer->setEmail(self::CUSTOMER_EMAIL);
        $customer->setGroup($this->getReference(CustomerGroupFixture::RETAIL_CUSTOMER_GROUP_REFERENCE, CustomerGroup::class));
        $manager->persist($customer);
        $manager->flush();

        $this->addReference(self::CUSTOMER_REFERENCE, $customer);
    }
}
