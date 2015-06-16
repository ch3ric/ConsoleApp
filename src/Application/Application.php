<?php

namespace Application;

use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

/**
 * Application.
 */
class Application extends BaseApplication
{
    const NAME = 'Example';
    const VERSION = '0.0.1-dev';

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * Constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;

        parent::__construct(self::NAME, self::VERSION);

        $this->setDispatcher($this->container->get('event_dispatcher'));

        $this->getDefinition()->addOption(new InputOption('--env', '-e', InputOption::VALUE_REQUIRED, 'The Environment name.', 'dev'));
        $this->getDefinition()->addOption(new InputOption('--no-debug', null, InputOption::VALUE_NONE, 'Switches off debug mode.'));
    }

    /**
     * Runs the current application.
     *
     * @param InputInterface  $input  An Input instance
     * @param OutputInterface $output An Output instance
     *
     * @return int 0 if everything went fine, or an error code
     */
    public function doRun(InputInterface $input, OutputInterface $output)
    {
        $this->registerCommands();

        return parent::doRun($input, $output);
    }

    /**
     * Register commands into the application
     */
    protected function registerCommands()
    {
        if ($this->container->hasParameter('app.console.command.ids')) {
            foreach ($this->container->getParameter('app.console.command.ids') as $id) {
                $this->add($this->container->get($id));
            }
        }
    }
}
