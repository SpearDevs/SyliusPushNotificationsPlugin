<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\Form\Type\Admin;

use Sylius\Component\Core\Model\ShopUser;
use Sylius\Component\Customer\Model\CustomerGroup;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
                'label' => 'speardevs.ui.title',
                'attr' => [
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('body', TextareaType::class, [
                'label' => 'speardevs.ui.content',
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 4,
                ],
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('receiver', ChoiceType::class, [
                'label' => 'speardevs.ui.receiver',
                'choices'  => [
                    'speardevs.ui.group' => 'group',
                    'speardevs.ui.user' => 'user',
                ],
            ])
            ->add('groups', EntityType::class, [
                'label' => 'speardevs.ui.group',
                'class' => CustomerGroup::class,
                'required' => false,
                'placeholder' => $this->translator->trans(
                    'speardevs.ui.all', [], 'messages'
                ),
            ])
            ->add('user', EntityType::class, [
                'label' => 'speardevs.ui.user',
                'class' => ShopUser::class,
                'required' => false,
                'placeholder' => $this->translator->trans(
                    'speardevs.ui.choose_user', [], 'messages'
                ),
            ]);
    }
}
