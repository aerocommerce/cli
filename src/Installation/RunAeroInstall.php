<?php

namespace Aero\Cli\Installation;

use Aero\Cli\InstallStep;
use Symfony\Component\Process\Process;

class RunAeroInstall extends InstallStep
{
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
