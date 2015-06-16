<?php

namespace Application\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 * CompilerPass to add the log level parameter that will be used for the default logger
 */
class LogLevelPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $levelId = 'monolog.logger.level.error';

        if ($container->getParameter('app.debug')) {
            $levelId = 'monolog.logger.level.debug';
        }

        $container->setParameter('monolog.logger.level', $container->getParameter($levelId));
    }
}
