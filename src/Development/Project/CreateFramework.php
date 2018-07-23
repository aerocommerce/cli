<?php

namespace Aero\Cli\Development\Project;

use Aero\Cli\DevCommand;
use Symfony\Component\Process\Process;

class CreateFramework
{
    private $path;
    private $command;

    public function __construct(DevCommand $command, $path)
    {
        $this->command = $command;
        $this->path = expand_tilde($path);
    }

    public function install()
    {
        mkdir($this->path . '/framework');

        $process = new Process('git clone git@github.com:aerocommerce/framework.git .', $this->path . '/framework');
        $process->run(function($type, $line) {
            $this->command->output->write($line);
        });

        $composer = new Process('composer install', $this->path . '/framework');
        $composer->run(function($type, $line) {
            $this->command->output->write($line);
        });
    }
}