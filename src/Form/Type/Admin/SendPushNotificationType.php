<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\Form\Type\Admin;

use SpearDevs\SyliusPushNotificationsPlugin\Form\Model\SendPushNotificationFormModel;
use Sylius\Component\Core\Model\Channel;
use Sylius\Component\Customer\Model\CustomerGroup;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class SendPushNotificationType extends AbstractType
{
    public function __construct(private TranslatorInterface $translator)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'speardevs_sylius_push_notifications_plugin.ui.title',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('body', TextareaType::class, [
                'label' => 'speardevs_sylius_push_notifications_plugin.ui.content',
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 4,
                ],
            ])
            ->add('channel', EntityType::class, [
                'label' => 'sylius.ui.channel',
                'required' => true,
                'class' => Channel::class,
            ])
            ->add('receiver', ChoiceType::class, [
                'label' => 'speardevs_sylius_push_notifications_plugin.ui.receiver',
                'choices' => [
                    'speardevs_sylius_push_notifications_plugin.ui.group' => 'group',
                    'speardevs_sylius_push_notifications_plugin.ui.user' => 'user',
                ],
            ])
            ->add('group', EntityType::class, [
                'label' => 'speardevs_sylius_push_notifications_plugin.ui.send_to',
                'class' => CustomerGroup::class,
                'required' => false,
                'placeholder' => $this->translator->trans(
                    'speardevs_sylius_push_notifications_plugin.ui.all',
                    [],
                    'messages',
                ),
            ])
            ->add('userEmail', EmailType::class, [
                'label' => 'speardevs_sylius_push_notifications_plugin.ui.user',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SendPushNotificationFormModel::class,
        ]);
    }
}
