<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\WebPush;

use Symfony\Component\HttpFoundation\Response;

final class WebPushException extends \Exception
{
    public function __construct(string $message, int $code = Response::HTTP_NOT_FOUND, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
