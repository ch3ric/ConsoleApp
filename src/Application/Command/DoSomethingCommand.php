<?php

namespace Application\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;

/**
 * Do something
 */
class DoSomethingCommand extends Command
{
    /**
     * Configure
     */
    protected function configure()
    {
        $this->setName('app:something')
            ->setDescription('Do something');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Yeah, I did it!');
    }
}
