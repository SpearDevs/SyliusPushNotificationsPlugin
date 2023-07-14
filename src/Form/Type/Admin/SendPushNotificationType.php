<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\Form\Type\Admin;

use SpearDevs\SyliusPushNotificationsPlugin\Entity\UserSubscription\UserSubscription;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Component\Channel\Model\Channel;
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
            ->add('channel', EntityType::class, [
                'label' => 'sylius.ui.channel',
                'required' => true,
                'class' => Channel::class,
                'constraints' => [
                    new NotBlank([
                        'groups' => 'sylius',
                    ]),
                ],
            ])
            ->add('receiver', ChoiceType::class, [
                'label' => 'speardevs_sylius_push_notifications_plugin.ui.receiver',
                'choices' => [
                    'speardevs_sylius_push_notifications_plugin.ui.group' => 'group',
                    'speardevs_sylius_push_notifications_plugin.ui.user' => 'user',
                ],
            ])
            ->add('groups', EntityType::class, [
                'label' => 'speardevs_sylius_push_notifications_plugin.ui.send_to',
                'class' => CustomerGroup::class,
                'required' => false,
                'placeholder' => $this->translator->trans(
                    'speardevs_sylius_push_notifications_plugin.ui.all',
                    [],
                    'messages',
                ),
            ])
            ->add('user', EntityType::class, [
                'label' => 'speardevs_sylius_push_notifications_plugin.ui.user',
                'class' => ShopUser::class,
                'required' => false,
                'placeholder' => $this->translator->trans(
                    'speardevs_sylius_push_notifications_plugin.ui.choose_user',
                    [],
                    'messages',
                ),
                'query_builder' => function (EntityRepository $shopUserRepository) {
                    return $shopUserRepository->createQueryBuilder('shopUser')
                        ->leftJoin(
                            UserSubscription::class,
                            'userSubscription',
                            'WITH',
                            'userSubscription.user = shopUser.id',
                        )
                        ->where('userSubscription IS NOT NULL');
                },
            ]);
    }
}
