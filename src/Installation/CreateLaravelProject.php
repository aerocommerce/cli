<?php

namespace Aero\Cli\Installation;

use Aero\Cli\Command;
use Aero\Cli\InstallStep;
use Symfony\Component\Process\Process;

class CreateLaravelProject extends InstallStep
{
    /**
     * Create a new installation helper instance.
     *
     * @param \Aero\Cli\Command $command
     */
    public function __construct(Command $command)
    {
        parent::__construct($command);

        $this->command->output->write('Installing Laravel');
    }

    /**
     * Run the installation helper.
     *
     * @return void
     */
    public function install()
    {
        $process = new Process('laravel new '.$this->command->project.' --quiet');

        if ('\\' !== DIRECTORY_SEPARATOR && file_exists('/dev/tty') && is_readable('/dev/tty')) {
            $process->setTty(true);
        }

        $process->setTimeout(null)->run(function ($type, $line) {
            $this->command->output->write($line);
        });

        if (! $process->isSuccessful()) {
            $this->command->output->writeln(': <error>✘</error>');
            die(0);
        }

        $this->command->output->writeln(': <info>✔</info>');
    }
}
