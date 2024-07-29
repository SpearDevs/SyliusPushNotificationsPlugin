<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\Form\Model;

use SpearDevs\SyliusPushNotificationsPlugin\Validator\Constraints\PushNotificationUserSubscriptionExists;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Customer\Model\CustomerGroupInterface;
use Symfony\Component\Validator\Constraints as Assert;

/** @PushNotificationUserSubscriptionExists() */
final class SendPushNotificationFormModel
{
    /** @Assert\NotBlank() */
    public string $title;

    /** @Assert\NotBlank() */
    public string $body;

    /** @Assert\NotBlank() */
    public ChannelInterface $channel;

    public string $receiver;

    public ?CustomerGroupInterface $group = null;

    /** @Assert\Email() */
    public string $userEmail;
}
