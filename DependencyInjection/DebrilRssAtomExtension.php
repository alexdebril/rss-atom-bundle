<?php declare(strict_types=1);

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
    public function load(array $configs, ContainerBuilder $container) : void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');

        $this->setDateFormats($container, $config);
        $container->setParameter('debril_rss_atom.private_feeds', $config['private']);
        $container->setParameter('debril_rss_atom.force_refresh', $config['force_refresh']);
        $container->setParameter('debril_rss_atom.content_type_json', $config['content_type_json']);
        $container->setParameter('debril_rss_atom.content_type_xml', $config['content_type_xml']);
    }

    /**
     * @param ContainerBuilder $container
     * @param array $config
     * @return $this
     */
    protected function setDateFormats(ContainerBuilder $container, array $config) : self
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
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container) : void
    {
        $this->setDefinition($container, 'logger', 'Psr\Log\NullLogger');
    }

    /**
     * @param ContainerBuilder $container
     * @param string $serviceName
     * @param string $className
     * @return DebrilRssAtomExtension
     */
    protected function setDefinition(ContainerBuilder $container, string $serviceName, string $className) : self
    {
        if ( ! $container->has($serviceName) ) {
            $container->setDefinition($serviceName, new Definition($className));
        }

        return $this;
    }
}
