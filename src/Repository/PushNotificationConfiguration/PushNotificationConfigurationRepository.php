<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\Repository\PushNotificationConfiguration;

use SpearDevs\SyliusPushNotificationsPlugin\Entity\PushNotificationConfiguration\PushNotificationConfigurationInterface;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

final class PushNotificationConfigurationRepository extends EntityRepository implements PushNotificationConfigurationRepositoryInterface
{
    public function save(PushNotificationConfigurationInterface $pushNotificationConfiguration): void
    {
        $this->_em->persist($pushNotificationConfiguration);
        $this->_em->flush();
    }
}
