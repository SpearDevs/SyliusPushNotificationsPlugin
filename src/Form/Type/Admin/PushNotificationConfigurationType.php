<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\Form\Type\Admin;

use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;

class PushNotificationConfigurationType extends AbstractResourceType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('icon', FileType::class, [
                'label' => 'speardevs_sylius_push_notifications_plugin.ui.iconPath',
            ]);
    }
}
