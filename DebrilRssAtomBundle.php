<?php

namespace Debril\RssAtomBundle;

use Debril\RssAtomBundle\DependencyInjection\CompilerPass\DriverCompilerPass;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class DebrilRssAtomBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new DriverCompilerPass());
    }
}
