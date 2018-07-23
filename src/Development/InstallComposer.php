<?php

namespace Aero\Cli\Development;

use Aero\Cli\NewCommand;
use Symfony\Component\Process\Process;

class InstallComposer
{
    private $command;

    public function __construct(NewCommand $command)
    {
        $this->command = $command;
    }

    public function install()
    {
        $this->command->output->writeln('<info>Installing Composer</info>');
        if ($this->composerDoestNotExist()) {
            $composer = new Process('brew install composer');
            $composer->run(function ($type, $line) {
                $this->command->output->write($line);
            });
        }
    }

    /**
     * @return bool
     */
    private function composerDoestNotExist()
    {
        $process = new Process('which composer');
        $process->run();

        return $process->getOutput() == '';
    }
}
