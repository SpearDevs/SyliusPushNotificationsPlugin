<?php

declare(strict_types=1);

namespace Tests\SpearDevs\SyliusPushNotificationsPlugin\Integration\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Sylius\Component\Locale\Model\Locale;

final class LocaleFixture extends Fixture
{
    public const LOCALE_REFERENCE = 'locale';

    public const LOCALE_CODE = 'pl_PL';

    public function load(ObjectManager $manager): void
    {
        $locale = new Locale();

        $locale->setCode(self::LOCALE_CODE);
        $manager->persist($locale);
        $manager->flush();

        $this->addReference(self::LOCALE_REFERENCE, $locale);
    }
}
