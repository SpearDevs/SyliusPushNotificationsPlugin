<?php

declare(strict_types=1);

namespace Tests\SpearDevs\SyliusPushNotificationsPlugin\Integration\ChannelContext;

use SpearDevs\SyliusPushNotificationsPlugin\Context\ChannelContext;
use Sylius\Bundle\ChannelBundle\Doctrine\ORM\ChannelRepository;
use Tests\SpearDevs\SyliusPushNotificationsPlugin\Integration\DataFixtures\ChannelFixture;
use Tests\SpearDevs\SyliusPushNotificationsPlugin\Integration\DataFixtures\CurrencyFixture;
use Tests\SpearDevs\SyliusPushNotificationsPlugin\Integration\DataFixtures\LocaleFixture;
use Tests\SpearDevs\SyliusPushNotificationsPlugin\Integration\IntegrationTestCase;

final class ChannelContextTest extends IntegrationTestCase
{
    private ChannelContext $channelContext;

    private ChannelRepository $channelRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->channelRepository = self::getContainer()->get(ChannelRepository::class);
        $this->channelContext = self::getContainer()->get(ChannelContext::class);
    }

    public function testSetChannel(): void
    {
        // Given
        $this->loadFixtures(
            [
                new LocaleFixture(),
                new CurrencyFixture(),
                new ChannelFixture(),
            ],
        );

        $channel = $this->channelRepository->findOneBy(['code' => ChannelFixture::CHANNEL_CODE]);

        //When
        $this->channelContext->setChannelCode(ChannelFixture::CHANNEL_CODE);
        $channelFromContext = $this->channelContext->getChannel();
        $channelCodeFromContext = $this->channelContext->getChannelCode();

        //Then
        self::assertSame($channel, $channelFromContext);
        self::assertSame($channel->getCode(), $channelCodeFromContext);
    }
}
