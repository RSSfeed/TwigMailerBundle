<?php

namespace ITF\TwigMailerBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('itf_twig_mailer');

        $rootNode
            ->children()
                ->scalarNode('sender_mail')->defaultValue('noreply@system.com')->end()
                ->scalarNode('layout')->defaultNull()->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
