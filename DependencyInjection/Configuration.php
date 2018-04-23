<?php

namespace Victoire\Widget\FormBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
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
        $rootNode = $treeBuilder->root('victoire_widget_form');

        $rootNode
            ->children()
                ->scalarNode('default_email_address')
                ->end()
                ->scalarNode('default_email_label')
                ->end()
                ->scalarNode('akismet_api_key')
                ->end()
                ->scalarNode('default_bcc_email_address')
                ->end()
                ->scalarNode('recaptcha_public_key')
                ->defaultNull()
                ->end()
                ->scalarNode('recaptcha_private_key')
                ->defaultNull()
                ->end()
                ->arrayNode('prefill')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('title')
                            ->end()
                            ->scalarNode('position')
                            ->end()
                            ->booleanNode('required')
                            ->end()
                            ->scalarNode('type')
                            ->end()
                            ->scalarNode('proposal')
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
