<?php

declare(strict_types=1);

namespace Tests\SpearDevs\SyliusPushNotificationsPlugin\Integration\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Sylius\Component\Customer\Model\CustomerGroup;

final class CustomerGroupFixture extends Fixture
{
    public const RETAIL_CUSTOMER_GROUP_REFERENCE = 'retail_group';

    public const RETAIL_CUSTOMER_GROUP_CODE = 'retail';

    public const RETAIL_CUSTOMER_GROUP_NAME = 'Retail';

    public const WHOLESALE_CUSTOMER_GROUP_REFERENCE = 'wholesale_group';

    public const WHOLESALE_CUSTOMER_GROUP_CODE = 'wholesale';

    public const WHOLESALE_CUSTOMER_GROUP_NAME = 'Wholesale';

    public function load(ObjectManager $manager): void
    {
        $retailGroup = new CustomerGroup();

        $retailGroup->setCode(self::RETAIL_CUSTOMER_GROUP_CODE);
        $retailGroup->setName(self::RETAIL_CUSTOMER_GROUP_NAME);
        $manager->persist($retailGroup);

        $wholesaleGroup = new CustomerGroup();

        $wholesaleGroup->setCode(self::WHOLESALE_CUSTOMER_GROUP_CODE);
        $wholesaleGroup->setName(self::WHOLESALE_CUSTOMER_GROUP_NAME);
        $manager->persist($wholesaleGroup);

        $manager->flush();

        $this->addReference(self::RETAIL_CUSTOMER_GROUP_REFERENCE, $retailGroup);
        $this->addReference(self::WHOLESALE_CUSTOMER_GROUP_REFERENCE, $wholesaleGroup);
    }
}
