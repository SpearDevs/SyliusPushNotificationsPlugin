<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\Form\Type\Admin;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class SendPushNotificationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'speardevs_sylius_push_notification_plugin.ui.title',
                'attr' => [
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('body', TextareaType::class, [
                'label' => 'speardevs_sylius_push_notification_plugin.ui.content',
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 4,
                ],
                'constraints' => [
                    new NotBlank()
                ]
            ]);
    }
}
