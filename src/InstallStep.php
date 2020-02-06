<?php

namespace Aero\Cli;

use Symfony\Component\Process\Process;

abstract class InstallStep implements InstallStepInterface
{
    /**
     * @var \Aero\Cli\Command
     */
    protected $command;

    public function __construct(Command $command)
    {
        $this->command = $command;
    }

    protected function errorInstall(?string $message = null): void
    {
        $this->command->output->error($message ?: 'Installation Failed');

        die(1);
    }

    protected function findComposer(): string
    {
        $composerPath = getcwd().'/composer.phar';

        if (file_exists($composerPath)) {
            return '"'.PHP_BINARY.'" '.$composerPath;
        }

        return 'composer';
    }

    protected function runCommand(array $command): void
    {
        $process = new Process($command, null, null, null, null);

        if ('\\' !== DIRECTORY_SEPARATOR && file_exists('/dev/tty') && is_readable('/dev/tty')) {
            $process->setTty(true);
        }

        $process->setTimeout(null)->run(function ($_, $line) {
            $this->command->output->write($line);
        });

        if (! $process->isSuccessful()) {
            $this->errorInstall();
        }
    }
}
