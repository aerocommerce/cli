<?php

namespace Aero\Cli\Development;

use Aero\Cli\DevCommand;
use Symfony\Component\Process\Process;

class InstallPHP
{
    public function __construct(DevCommand $command)
    {
        $this->command = $command;
    }

    public function install()
    {
        $this->command->output->writeln('<info>Installing PHP 7.2</info>');

        $php = new Process('brew install php@7.2');

        $php->run(function ($type, $line) {
            $this->command->output->write($line);
        });
    }
}
