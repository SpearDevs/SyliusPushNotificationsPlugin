<?php

declare(strict_types=1);

namespace Tests\SpearDevs\SyliusPushNotificationsPlugin\Integration\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Sylius\Component\Core\Model\Channel;
use Sylius\Component\Currency\Model\Currency;
use Sylius\Component\Locale\Model\Locale;

final class ChannelFixture extends Fixture
{
    public const CHANNEL_REFERENCE = 'channel';

    public const CHANNEL_CODE = 'test_channel_code';

    public const CHANNEL_NAME = 'test_channel_code';

    public const TAX_CALCULATION_STRATEGY = 'order_items_based';

    public function load(ObjectManager $manager): void
    {
        $channel = new Channel();

        $channel->setCode(self::CHANNEL_CODE);
        $channel->setName(self::CHANNEL_NAME);
        $channel->setTaxCalculationStrategy(self::TAX_CALCULATION_STRATEGY);
        $channel->setDefaultLocale(
            $this->getReference(LocaleFixture::LOCALE_REFERENCE, Locale::class),
        );
        $channel->setBaseCurrency(
            $this->getReference(CurrencyFixture::CURRENCY_REFERENCE, Currency::class),
        );
        $manager->persist($channel);
        $manager->flush();

        $this->addReference(self::CHANNEL_REFERENCE, $channel);
    }
}
