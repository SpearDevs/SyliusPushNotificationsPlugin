<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\Form\Type\Admin;

use Sylius\Component\Customer\Model\CustomerGroup;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Contracts\Translation\TranslatorInterface;

class SendPushNotificationType extends AbstractType
{
    public function __construct(private TranslatorInterface $translator) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'speardevs_sylius_push_notifications_plugin.ui.title',
                'attr' => [
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('body', TextareaType::class, [
                'label' => 'speardevs_sylius_push_notifications_plugin.ui.content',
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 4,
                ],
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('groups', EntityType::class, [
                'label' => 'speardevs_sylius_push_notifications_plugin.ui.send_to',
                'class' => CustomerGroup::class,
                'expanded' => true,
                'required' => false,
                'placeholder' => $this->translator->trans(
                    'speardevs_sylius_push_notifications_plugin.ui.all', [], 'messages'
                ),
            ]);
    }
}
