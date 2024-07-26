<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\Form\Model;

use SpearDevs\SyliusPushNotificationsPlugin\Validator\Constraints\PushNotificationUser;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Customer\Model\CustomerGroupInterface;
use Symfony\Component\Validator\Constraints as Assert;

/** @PushNotificationUser() */
final class SendPushNotificationFormModel
{
    /** @Assert\NotBlank() */
    public ?string $title = null;

    /** @Assert\NotBlank() */
    public ?string $body = null;

    /** @Assert\NotBlank() */
    public ?ChannelInterface $channel = null;

    public ?string $receiver = null;

    public ?CustomerGroupInterface $group = null;

    /** @Assert\Email() */
    public ?string $userEmail = null;
}
