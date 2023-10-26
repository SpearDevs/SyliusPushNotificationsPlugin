<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\Factory;

use BenTools\WebPushBundle\Model\Response\PushResponse;
use SpearDevs\SyliusPushNotificationsPlugin\Context\ChannelContextInterface;
use SpearDevs\SyliusPushNotificationsPlugin\Entity\PushNotificationHistory\PushNotificationHistoryInterface;
use Sylius\Component\Core\Model\Channel;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\User\Model\User;
use Webmozart\Assert\Assert;

final class PushNotificationHistoryFactory implements PushNotificationHistoryFactoryInterface
{
    public function __construct(
        private FactoryInterface $factory,
        private ChannelContextInterface $channelContext,
    ) {
    }

    public function createNew(): PushNotificationHistoryInterface
    {
        /** @var PushNotificationHistoryInterface $pushNotificationHistory */
        $pushNotificationHistory = $this->factory->createNew();
        Assert::isInstanceOf($pushNotificationHistory, PushNotificationHistoryInterface::class);

        return $pushNotificationHistory;
    }

    public function createNewWithPushNotificationData(
        string $pushTitle,
        string $pushContent,
        PushResponse $pushResponse,
    ): PushNotificationHistoryInterface {
        $subscription = $pushResponse->getSubscription();

        $pushNotificationHistory = $this->createNew();
        $pushNotificationHistory->setTitle($pushTitle);
        $pushNotificationHistory->setContent($pushContent);

        /** @var User $user */
        $user = $subscription->getUser();
        $pushNotificationHistory->setUser($user);
        $pushNotificationHistory->setResponseStatusCode($pushResponse->getStatusCode());

        /** @var Channel $channel * */
        $channel = $this->channelContext->getChannel();
        $pushNotificationHistory->setChannel($channel);

        return $pushNotificationHistory;
    }
}
