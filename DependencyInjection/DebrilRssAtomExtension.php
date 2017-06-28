<?php

namespace Debril\RssAtomBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class DebrilRssAtomExtension extends Extension implements CompilerPassInterface
{

    /**
     * @var array
     */
    protected $defaultDateFormats = [
        \DateTime::RFC3339,
        \DateTime::RSS,
        \DateTime::W3C,
        'Y-m-d\TH:i:s.uP',
        'Y-m-d',
        'd/m/Y',
        'd M Y H:i:s P',
        'D, d M Y H:i O',
        'D, d M Y H:i:s O',
        'D M d Y H:i:s e',
    ];

    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');

        $this->setDateFormats($container, $config);
        $container->setParameter('debril_rss_atom.private_feeds', $config['private']);
        $container->setParameter('debril_rss_atom.force_refresh', $config['force_refresh']);
    }

    /**
     * @param ContainerBuilder $container
     * @param array $config
     * @return $this
     */
    protected function setDateFormats(ContainerBuilder $container, array $config)
    {
        $dateFormats = isset($config['date_formats']) ?
            array_merge($this->defaultDateFormats, $config['date_formats']):
            $this->defaultDateFormats;

        $container->setParameter(
            'debril_rss_atom.date_formats',
            $dateFormats
        );

        return $this;
    }

    /**
     * @param ContainerBuilder $builder
     */
    public function process(ContainerBuilder $container)
    {
        $this->setDefinition($container, 'logger', 'Psr\Log\NullLogger');
    }

    /**
     * @param ContainerBuilder $container
     * @param $serviceName
     * @param $className
     * @return $this
     */
    protected function setDefinition(ContainerBuilder $container, $serviceName, $className)
    {
        if ( ! $container->has($serviceName) ) {
            $container->setDefinition($serviceName, new Definition($className));
        }

        return $this;
    }
}
