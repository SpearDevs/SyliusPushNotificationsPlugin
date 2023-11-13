<?php

declare(strict_types=1);

namespace Tests\SpearDevs\SyliusPushNotificationsPlugin\Integration\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Sylius\Component\Currency\Model\Currency;

final class CurrencyFixture extends Fixture
{
    public const CURRENCY_REFERENCE = 'currency';

    public const CURRENCY_CODE = 'PLN';

    public function load(ObjectManager $manager): void
    {
        $currency = new Currency();

        $currency->setCode(self::CURRENCY_CODE);
        $manager->persist($currency);
        $manager->flush();

        $this->addReference(self::CURRENCY_REFERENCE, $currency);
    }
}
