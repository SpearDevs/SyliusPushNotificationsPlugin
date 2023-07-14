<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait EntityIdTrait
{
    /**
     * @ORM\Column(type="integer")
     *
     * @ORM\Id
     *
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private ?int $id;

    public function getId(): ?int
    {
        return $this->id;
    }
}
