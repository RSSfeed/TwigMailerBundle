<?php

namespace ITF\TwigMailerBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
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
                ->scalarNode('layout')->defaultValue('@ITFTwigMailer/Default/layout.html.twig')->end()
                ->scalarNode('sender')->defaultValue('noreply@system.com')->end()
                ->scalarNode('mail_template_class')->cannotBeEmpty()->end()
            ->end()
        ;
        
        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        return $treeBuilder;
    }
}
