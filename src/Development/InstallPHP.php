<?php

namespace Aero\Cli\Development;

use Aero\Cli\NewCommand;
use Symfony\Component\Process\Process;

class InstallPHP
{
    public function __construct(NewCommand $command)
    {
        $this->command = $command;
    }

    public function install()
    {
        $this->command->output->writeln("<info>Installing PHP 7.1</info>");
        $php = new Process('brew install php@7.1');
        $php->run(function ($type, $line) {
            $this->command->output->write($line);
        });
    }
}