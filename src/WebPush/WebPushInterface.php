<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\WebPush;

interface WebPushInterface
{
    public function getTitle(): string;

    public function getContent(): string;
}
