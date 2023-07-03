<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\Twig\Extension;

use SpearDevs\SyliusPushNotificationsPlugin\Repository\PushNotificationHistory\PushNotificationHistoryRepository;
use Sylius\Component\Core\Model\ShopUserInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

final class PushNotificationHistoryTwigExtension extends AbstractExtension
{
    public function __construct(
        private PushNotificationHistoryRepository $pushNotificationHistoryRepository
    ) {
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('countOfNotReceivedPushNotifications', [$this, 'getCountOfNotReceivedPushNotifications']),
        ];
    }

    public function getCountOfNotReceivedPushNotifications(ShopUserInterface $user): int
    {
        return $this->pushNotificationHistoryRepository->getCountOfNotReceivedPushNotifications($user);
    }
}
