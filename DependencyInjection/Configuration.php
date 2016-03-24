<?php

namespace Debril\RssAtomBundle\DependencyInjection;

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
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();

        $treeBuilder->root('debril_rss_atom')
                ->children()
                    ->booleanNode('private')
                        ->info('Change cache headers so the RSS feed is not cached by public caches (like reverse-proxies...).')
                        ->defaultValue(false)
                    ->end()
                    ->arrayNode('date_formats')
                        ->prototype('scalar')->end()
                    ->end()
                    ->enumNode('driver')
                        ->info('Driver to use to fetch RSS feed. Valid values are "curl" (default), "file", "guzzle", "service".')
                        ->values(array('curl', 'file', 'guzzle', 'service'))
                        ->defaultValue('curl')
                    ->end()
                    ->scalarNode('driver_service')
                        ->info('If driver is set to "csa-guzzle" or "service", the ID of the service to use')
                    ->end()
                    ->arrayNode('curlopt')
                        ->info('Parameters for curl requests')
                        ->children()
                            ->scalarNode('timeout')
                                ->info('Timeout in seconds for curl requests')
                            ->end()
                            ->scalarNode('useragent')
                                ->info('User agent for curl requests')
                            ->end()
                            ->scalarNode('maxredirs')
                                ->info('Maximum redirects for curl requests')
                            ->end()
                        ->end()
                    ->end()
                ->end()
        ;

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.
        return $treeBuilder;
    }
}
