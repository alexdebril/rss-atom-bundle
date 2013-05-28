<?php

namespace Debril\RssAtomBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class DebrilRssAtomExtension extends Extension
{

    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');

        if (!isset($config['feed_provider']))
        {
            throw new \InvalidArgumentException(
            'The "feed_provider" option must be set'
            );
        }

        $container->setParameter(
                'debril_rss_atom.feed_provider', $config['feed_provider']
        );

        $default = array(
            \DateTime::RFC3339,
            \DateTime::RSS,
        );

        if (!isset($config['date_formats']))
        {
            $container->setParameter(
                    'debril_rss_atom.date_formats', $default
            );
        } else
        {
            $container->setParameter(
                    'debril_rss_atom.date_formats', array_merge($default, $config['date_formats'])
            );
        }
    }

}
