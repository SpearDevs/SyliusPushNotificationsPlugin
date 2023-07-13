<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\Factory;

use BenTools\WebPushBundle\Model\Response\PushResponse;
use SpearDevs\SyliusPushNotificationsPlugin\Entity\PushNotificationHistory\PushNotificationHistoryInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Webmozart\Assert\Assert;
use Sylius\Component\User\Model\User;

final class PushNotificationHistoryFactory implements FactoryInterface
{
    public function __construct(private FactoryInterface $factory) {
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
        PushResponse $pushResponse
    ): PushNotificationHistoryInterface {
        $subscription = $pushResponse->getSubscription();

        $pushNotificationHistory = $this->createNew();
        $pushNotificationHistory->setTitle($pushTitle);
        $pushNotificationHistory->setContent($pushContent);

        /** @var User $user */
        $user = $subscription->getUser();
        $pushNotificationHistory->setUser($user);
        $pushNotificationHistory->setResponseStatusCode($pushResponse->getStatusCode());

        return $pushNotificationHistory;
    }
}
