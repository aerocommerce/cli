<?php

namespace Aero\Cli\Development;

use Aero\Cli\NewCommand;
use Symfony\Component\Process\Process;

class InstallElasticSearch
{
    private $command;

    public function __construct(NewCommand $command)
    {
        $this->command = $command;
    }

    public function install()
    {
        $elastic = new Process('brew install elasticsearch');

        $elastic->run(function($type, $line) {
            $this->command->output->write($line);
        });
    }
}