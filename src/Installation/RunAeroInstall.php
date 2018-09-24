<?php

namespace Aero\Cli\Installation;

use Aero\Cli\NewCommand;
use Symfony\Component\Process\Process;

class RunAeroInstall
{
    protected $command;

    protected $name;

    /**
     * Create a new installation helper instance.
     *
     * @param NewCommand $command
     * @param  string $name
     */
    public function __construct(NewCommand $command, $name)
    {
        $this->name = $name;
        $this->command = $command;
    }

    /**
     * Run the installation helper.
     *
     * @return void
     */
    public function install()
    {
        $process = new Process($this->command(), $this->command->path);

        if ('\\' !== DIRECTORY_SEPARATOR && file_exists('/dev/tty') && is_readable('/dev/tty')) {
            $process->setTty(true);
        }

        $process->setTimeout(null)->run(function ($type, $line) {
            $this->command->output->write($line);
        });
    }

    /**
     * Get the proper Aero installation command.
     *
     * @return string
     */
    protected function command()
    {
        $command = 'php artisan aero:install --force';

        if ($this->command->input->getOption('internal')) {
            $command .= ' --internal';
        }

        return $command;
    }
}
