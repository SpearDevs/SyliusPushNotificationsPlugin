<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\Factory;

use SpearDevs\SyliusPushNotificationsPlugin\Entity\PushNotificationHistory\PushNotificationHistory;
use SpearDevs\SyliusPushNotificationsPlugin\Entity\UserSubscription\UserSubscription;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Webmozart\Assert\Assert;
use Sylius\Component\User\Model\User;

final class PushNotificationHistoryFactory implements FactoryInterface
{
    private FactoryInterface $decoratedFactory;

    public function __construct(FactoryInterface $factory)
    {
        $this->decoratedFactory = $factory;
    }

    public function createNew(): PushNotificationHistory
    {
        /** @var PushNotificationHistory $pushNotificationHistory */
        $pushNotificationHistory = $this->decoratedFactory->createNew();
        Assert::isInstanceOf($pushNotificationHistory, PushNotificationHistory::class);
        return $pushNotificationHistory;
    }

    public function createNewWithPushNotificationData($pushTitle, $pushContent, UserSubscription $subscription): PushNotificationHistory
    {
        $pushNotificationHistory = $this->createNew();
        $pushNotificationHistory->setTitle($pushTitle);
        $pushNotificationHistory->setContent($pushContent);

        /** @var User $user */
        $user = $subscription->getUser();
        $pushNotificationHistory->setUser($user);

        return $pushNotificationHistory;
    }
}
