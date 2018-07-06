<?php

namespace Aero\Cli\Installation;

use Aero\Cli\NewCommand;
use Symfony\Component\Process\Process;

class ComposerUpdate
{
    protected $command;

    /**
     * Create a new installation helper instance.
     *
     * @param  NewCommand $command
     * @return void
     */
    public function __construct(NewCommand $command)
    {
        $this->command = $command;
    }

    /**
     * Run the installation helper.
     *
     * @return void
     */
    public function install()
    {
        $process = (new Process('composer update', $this->command->path))->setTimeout(null);

        if ('\\' !== DIRECTORY_SEPARATOR && file_exists('/dev/tty') && is_readable('/dev/tty')) {
            $process->setTty(true);
        }

        $process->run(function ($type, $line) {
            $this->command->output->write($line);
        });
    }
}
