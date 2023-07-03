<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\Uploader;

use Webmozart\Assert\Assert;
use Gaufrette\Filesystem;
use SpearDevs\SyliusPushNotificationsPlugin\Entity\PushNotificationConfiguration\PushNotificationConfigurationInterface;
use SpearDevs\SyliusPushNotificationsPlugin\Generator\IconPathGenerator;
use SpearDevs\SyliusPushNotificationsPlugin\Repository\PushNotificationConfiguration\PushNotificationConfigurationRepository;

final class PushNotificationIconUploader
{
    public function __construct(
        private PushNotificationConfigurationRepository $pushNotificationConfigurationRepository,
        private IconPathGenerator $iconPathGenerator,
        private Filesystem $filesystem,
    ) {
    }

    public function upload(PushNotificationConfigurationInterface $configuration): void
    {
        if (!$configuration->hasIcon()) {
            return;
        }

        $icon = $configuration->getIcon();
        Assert::notNull($icon, 'Icon for configuration is null');

        if (null !== $configuration->getIconPath() && $this->has($configuration->getIconPath())) {
            $this->remove($configuration->getIconPath());
        }

        $path = $this->iconPathGenerator->generate($configuration);

        $configuration->setIconPath($path);

        /** @var string $fileContents */
        $fileContents = file_get_contents($icon->getPathname());

        $this->filesystem->write(
            $path,
            $fileContents
        );

        $this->pushNotificationConfigurationRepository->save($configuration);
    }

    public function remove(string $path): bool
    {
        if ($this->filesystem->has($path)) {
            return $this->filesystem->delete($path);
        }

        return false;
    }

    private function has(string $path): bool
    {
        return $this->filesystem->has($path);
    }
}
