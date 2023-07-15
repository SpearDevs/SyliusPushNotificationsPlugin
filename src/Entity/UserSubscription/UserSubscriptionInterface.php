<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\Entity\UserSubscription;

use BenTools\WebPushBundle\Model\Subscription\UserSubscriptionInterface as CoreUserSubscriptionInterface;
use Sylius\Component\Channel\Model\Channel;

interface UserSubscriptionInterface extends CoreUserSubscriptionInterface
{
    public function getChannel(): Channel;

    public function setChannel(Channel $channel): void;
}
