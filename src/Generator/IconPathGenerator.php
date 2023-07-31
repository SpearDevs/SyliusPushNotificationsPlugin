<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\Generator;

use SpearDevs\SyliusPushNotificationsPlugin\Entity\PushNotificationConfiguration\PushNotificationConfigurationInterface;
use Symfony\Component\HttpFoundation\File\File;

final class IconPathGenerator implements IconPathGeneratorInterface
{
    public function generate(PushNotificationConfigurationInterface $configuration): string
    {
        /** @var File $file */
        $file = $configuration->getIcon();

        $hash = bin2hex(random_bytes(16));

        return $this->expandPath(
            sprintf('%s.%s', $hash, $file->guessExtension()),
        );
    }

    private function expandPath(string $path): string
    {
        $firstTwoCharacters = substr($path, 0, 2);
        $secondTwoCharacters = substr($path, 2, 2);
        $remainingPart = substr($path, 4);

        return sprintf('/%s/%s/%s', $firstTwoCharacters, $secondTwoCharacters, $remainingPart);
    }
}
