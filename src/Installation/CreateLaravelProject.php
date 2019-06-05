<?php

namespace Aero\Cli\Installation;

use Aero\Cli\Command;
use Aero\Cli\InstallStep;
use Symfony\Component\Process\Process;

class CreateLaravelProject extends InstallStep
{
    /**
     * Create a new installation helper instance.
     *
     * @param \Aero\Cli\Command $command
     */
    public function __construct(Command $command)
    {
        parent::__construct($command);

        $this->command->output->writeln('');
    }

    /**
     * Run the installation helper.
     *
     * @return void
     */
    public function install()
    {
        $this->command->output->write('Downloading Laravel');

        $process = new Process([
            $this->findComposer(),
            'create-project',
            'laravel/laravel="5.8.*"',
            $this->command->project,
            '--quiet',
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

        $this->command->output->writeln(': <info>✔</info>');

        $composer = $this->findComposer();

        $commands = [
            $composer.' install --no-scripts',
            $composer.' run-script post-root-package-install --quiet',
            $composer.' run-script post-create-project-cmd --quiet',
            $composer.' run-script post-autoload-dump --quiet',
        ];

        $process = new Process([implode(' && ', $commands)]);

        if ('\\' !== DIRECTORY_SEPARATOR && file_exists('/dev/tty') && is_readable('/dev/tty')) {
            $process->setTty(true);
        }

        $process->run(function ($type, $line) {
            $this->command->output->write($line);
        });
    }
}
