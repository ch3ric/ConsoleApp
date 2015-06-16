<?php

namespace Application\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder as BaseContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\EventDispatcher\DependencyInjection\RegisterListenersPass;
use Application\DependencyInjection\Compiler\LogLevelPass;
use Application\DependencyInjection\Compiler\CommandIdsPass;
use OldSound\RabbitMqBundle\DependencyInjection\OldSoundRabbitMqExtension;
use OldSound\RabbitMqBundle\DependencyInjection\Compiler\RegisterPartsPass;

/**
 * Container Builder
 */
class ContainerBuilder extends BaseContainerBuilder
{
    private $appParametersSet = false;

    /**
     * Builds the container
     *
     * @return ContainerBuilder
     */
    public function build()
    {
        if (!$this->appParametersSet) {
            throw new \LogicException("The app parameters are needed to build. Please call 'setAppParameters' first.");
        }

        $this->registerExtension(new OldSoundRabbitMqExtension());

        $ymlLoader = new YamlFileLoader($this, new FileLocator($this->getParameter('app.root_dir') . '/app/config'));
        $ymlLoader->load('config.yml');

        $xmlLoader = new XmlFileLoader($this, new FileLocator($this->getParameter('app.root_dir') . '/src/Application/Resources/config'));
        $xmlLoader->load('services.xml');

        $this->addCompilerPass(new RegisterListenersPass());
        $this->addCompilerPass(new LogLevelPass());
        $this->addCompilerPass(new CommandIdsPass());
        $this->addCompilerPass(new RegisterPartsPass());

        $this->compile();

        return $this;
    }

    /**
     * @param boolean $debug
     * @param string  $env
     * @param string  $rootDir
     *
     * @return ContainerBuilder
     */
    public function setAppParameters($debug, $env, $rootDir)
    {
        $this->setParameter('app.debug', $debug);
        $this->setParameter('app.env', $env);
        $this->setParameter('app.root_dir', $rootDir);
        $this->setParameter('app.cache_dir', $rootDir . '/app/cache');
        $this->setParameter('app.logs_dir', $rootDir . '/app/logs');

        $this->appParametersSet = true;

        return $this;
    }
}
