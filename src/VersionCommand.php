<?php

namespace Aero\Cli;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class VersionCommand extends Command
{
    /**
     * Configure the command options.
     *
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('version')
            ->setDescription('Get the version of Aero Commerce installed in the current directory.');
    }

    /**
     * Execute the command.
     *
     * @param  InputInterface  $input
     * @param  OutputInterface  $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (! is_dir(getcwd().'/aero')) {
            throw new \RuntimeException('This does not appear to be an Aero Commerce project.');
        }

        (new Please($output))->run('version');
    }
}
