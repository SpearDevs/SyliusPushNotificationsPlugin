<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\Entity;

use Doctrine\ORM\Mapping as ORM;
use SpearDevs\SyliusPushNotificationsPlugin\Entity\Traits\EntityIdTrait;

/**
 * @ORM\Entity
 * @ORM\Table(name="speardevs_push_notification_template")
 */
class PushNotificationTemplate implements PushNotificationTemplateInterface
{
    use EntityIdTrait;

    /**
     * @ORM\Column(type="string")
     */
    private string $title;

    /**
     * @ORM\Column(type="text")
     */
    private string $content;

    /**
     * @ORM\Column(type="string")
     */
    private string $code;

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
    }
}
