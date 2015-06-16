<?php

namespace Application\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 * Compiler Pass to get all command ids as parameter
 */
class CommandIdsPass implements CompilerPassInterface
{
    /**
     * Get the taggued commands and add them to the parameter
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $container->setParameter(
            'app.console.command.ids',
            array_keys($container->findTaggedServiceIds('app.console.command'))
        );
    }
}
