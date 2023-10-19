<?php

declare(strict_types=1);

namespace Tests\SpearDevs\SyliusPushNotificationsPlugin\Unit\Generator;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SpearDevs\SyliusPushNotificationsPlugin\Entity\PushNotificationConfiguration\PushNotificationConfigurationInterface;
use SpearDevs\SyliusPushNotificationsPlugin\Generator\IconPathGenerator;
use SpearDevs\SyliusPushNotificationsPlugin\Generator\IconPathGeneratorInterface;
use Symfony\Component\HttpFoundation\File\File;

final class IconPathGeneratorTest extends TestCase
{
    /** @var IconPathGeneratorInterface&MockObject */
    private IconPathGeneratorInterface $iconPathGenerator;

    protected function setUp(): void
    {
        $this->iconPathGenerator = new IconPathGenerator();
    }

    public function testGenerateReturnsExpectedPath()
    {
        //Given
        $configuration = $this->createMock(PushNotificationConfigurationInterface::class);
        $iconFile = $this->createMock(File::class);

        $configuration->expects($this->once())
            ->method('getIcon')
            ->willReturn($iconFile);

        $iconFile->expects($this->once())
            ->method('guessExtension')
            ->willReturn('png');

        //When
        $path = $this->iconPathGenerator->generate($configuration);

        //Then
        $this->assertIsString($path);
    }
}
