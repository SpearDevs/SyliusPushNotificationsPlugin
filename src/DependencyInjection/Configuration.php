<?php

declare(strict_types=1);

namespace SpearDevs\SyliusPushNotificationsPlugin\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    /**
     * @psalm-suppress UnusedVariable
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('spear_devs_sylius_push_notifications_plugin');
        $rootNode = $treeBuilder->getRootNode();

        return $treeBuilder;
    }
}
