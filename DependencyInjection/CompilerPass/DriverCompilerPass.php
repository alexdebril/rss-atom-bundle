<?php

namespace Debril\RssAtomBundle\DependencyInjection\CompilerPass;

use Debril\RssAtomBundle\DependencyInjection\Configuration;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class DriverCompilerPass
 */
class DriverCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $configs = $container->getExtensionConfig('debril_rss_atom');

        $configTree = new Configuration();
        $processor = new Processor();
        $config = $processor->processConfiguration($configTree, $configs);

        switch ($config['driver']) {
            case 'curl':
                // nothing to do
                break;
            case 'file':
                $container
                    ->getDefinition('debril.reader')
                    ->replaceArgument(0, new Reference('debril.file'));

                break;
            case 'guzzle':
                if (! isset($config['driver_service'])) {
                    throw new \Exception('When setting debril_rss_atom.driver to "guzzle", you should provide Client service ID in debril_rss_atom.driver_service!');
                }

                $guzzlebridge = $container->getDefinition('debril.http.guzzle_bridge');
                $guzzlebridge->addArgument(new Reference($config['driver_service']));

                $container
                    ->getDefinition('debril.reader')
                    ->replaceArgument(0, $guzzlebridge);
                break;
            case 'service':
                if (! isset($config['driver_service'])) {
                    throw new \Exception('When setting debril_rss_atom.driver to "service", you should provide service ID in debril_rss_atom.driver_service!');
                }

                $container
                    ->getDefinition('debril.reader')
                    ->replaceArgument(0, new Reference($config['driver_service']));
                break;
            default:
                throw new \Exception('Unable to handle debril_rss_atom.driver value!');
        }
    }
}
