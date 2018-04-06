<?php

namespace Debril\RssAtomBundle\DependencyInjection;

use PHPUnit\Framework\TestCase;

class ConfigurationTest extends TestCase
{

    public function testGetConfigTreeBuilder()
    {
        $configuration = new Configuration();

        $tree = $configuration->getConfigTreeBuilder();

        $this->assertInstanceOf('\Symfony\Component\Config\Definition\Builder\TreeBuilder', $tree);
    }

}
