<?php

namespace Debril\RssAtomBundle\DependencyInjection;

class ConfigurationTest extends \PHPUnit_Framework_TestCase
{

    public function testGetConfigTreeBuilder()
    {
        $configuration = new Configuration();

        $tree = $configuration->getConfigTreeBuilder();

        $this->assertInstanceOf('\Symfony\Component\Config\Definition\Builder\TreeBuilder', $tree);
    }

}