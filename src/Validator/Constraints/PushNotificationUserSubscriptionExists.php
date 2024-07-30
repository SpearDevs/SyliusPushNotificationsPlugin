<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class PushNotificationUserSubscriptionExists extends Constraint
{
    public string $message = 'push_notifications.send.user_subscription_not_exist';

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
