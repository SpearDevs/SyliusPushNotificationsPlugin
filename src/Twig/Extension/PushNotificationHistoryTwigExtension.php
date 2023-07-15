<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\Twig\Extension;

use SpearDevs\SyliusPushNotificationsPlugin\Repository\PushNotificationHistory\PushNotificationHistoryRepositoryInterface;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\ShopUserInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

final class PushNotificationHistoryTwigExtension extends AbstractExtension
{
    public function __construct(
        private PushNotificationHistoryRepositoryInterface $pushNotificationHistoryRepository,
        private ChannelContextInterface $channelContext,
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
        $channel = $this->channelContext->getChannel();

        /** @var ChannelInterface $channel */
        return $this->pushNotificationHistoryRepository->getCountOfNotReceivedPushNotifications($user, $channel);
    }
}
