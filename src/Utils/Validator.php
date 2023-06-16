<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\Utils;

use Symfony\Component\Console\Exception\InvalidArgumentException;

final class Validator
{
    public function validateText(?string $value): string
    {
        if ($value === null) {
            throw new InvalidArgumentException('The value can not be empty.');
        }

        return $value;
    }
}
