<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\Fixture;

use SpearDevs\SyliusPushNotificationsPlugin\Entity\PushNotificationTemplate\PushNotificationTemplateInterface;
use SpearDevs\SyliusPushNotificationsPlugin\Repository\PushNotificationTemplate\PushNotificationTemplateRepositoryInterface;
use Sylius\Bundle\FixturesBundle\Fixture\AbstractFixture;
use Sylius\Bundle\FixturesBundle\Fixture\FixtureInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

final class PushNotificationTemplateFixture extends AbstractFixture implements FixtureInterface
{
    public function __construct(
        private FactoryInterface $pushNotificationTemplateFactory,
        private PushNotificationTemplateRepositoryInterface $pushNotificationTemplateRepository,
    ) {
    }

    public function load(array $options): void
    {
        foreach ($options['templates'] as $template) {
            /** @var PushNotificationTemplateInterface $pushNotificationTemplate */
            $pushNotificationTemplate = $this->pushNotificationTemplateFactory->createNew();
            $pushNotificationTemplate->setTitle($template['title']);
            $pushNotificationTemplate->setContent($template['content']);
            $pushNotificationTemplate->setCode($template['code']);
            $this->pushNotificationTemplateRepository->add($pushNotificationTemplate);
        }
    }

    public function getName(): string
    {
        return 'push_notification_template';
    }

    protected function configureOptionsNode(ArrayNodeDefinition $optionsNode): void
    {
        $optionsNode
            ->children()
                ->arrayNode('templates')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('title')->isRequired()->end()
                            ->scalarNode('content')->isRequired()->end()
                            ->scalarNode('code')->isRequired()->end()
                        ->end()
                    ->end()
            ->end();
    }
}
