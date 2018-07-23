<?php

namespace Aero\Cli\Development\Project;

use Aero\Cli\NewCommand;
use Symfony\Component\Process\Process;

class UpdateComposer
{
    protected $command;

    /**
     * Create a new installation helper instance.
     *
     * @param  NewCommand $command
     * @param             $path
     */
    public function __construct(NewCommand $command, $path)
    {
        $this->command = $command;
        $this->path = expand_tilde($path);
    }

    /**
     * Run the installation helper.
     *
     * @return void
     */
    public function install()
    {
        $process = (new Process('composer update', $this->path.'/aero'))->setTimeout(null);

        if ('\\' !== DIRECTORY_SEPARATOR && file_exists('/dev/tty') && is_readable('/dev/tty')) {
            $process->setTty(true);
        }

        $process->run(function ($type, $line) {
            $this->command->output->write($line);
        });
    }
}
