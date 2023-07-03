<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\Repository\PushNotificationConfiguration;

use SpearDevs\SyliusPushNotificationsPlugin\Entity\PushNotificationConfiguration\PushNotificationConfigurationInterface;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

final class PushNotificationConfigurationRepository extends EntityRepository
{
    public function save(PushNotificationConfigurationInterface $pushNotificationHistory): void
    {
        $this->_em->persist($pushNotificationHistory);
        $this->_em->flush();
    }
}
