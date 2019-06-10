<?php

namespace Aero\Cli\Installation;

use Aero\Cli\InstallStep;
use Symfony\Component\Process\Process;

class CreateProject extends InstallStep
{
    /**
     * Run the installation helper.
     *
     * @return void
     */
    public function install()
    {
        $this->command->output->write('Downloading base project...');

        $process = new Process([
            $this->findComposer(),
            'create-project',
            'laravel/laravel=5.8',
            $this->command->project,
            '--quiet',
            '--no-scripts',
            '--no-install',
        ]);

        if ('\\' !== DIRECTORY_SEPARATOR && file_exists('/dev/tty') && is_readable('/dev/tty')) {
            $process->setTty(true);
        }

        $process->setTimeout(null)->run(function ($type, $line) {
            $this->command->output->write($line);
        });

        if (! $process->isSuccessful()) {
            $this->errorInstall();
        }

        $this->command->output->writeln(' <info>✔</info>');
    }
}
