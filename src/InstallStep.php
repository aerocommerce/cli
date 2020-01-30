<?php

namespace Aero\Cli;

use Symfony\Component\Process\Process;

abstract class InstallStep implements InstallationStepInterface
{
    /**
     * The command instance.
     *
     * @var \Aero\Cli\Command
     */
    protected $command;

    /**
     * Create a new installation helper instance.
     *
     * @param \Aero\Cli\Command $command
     */
    public function __construct(Command $command)
    {
        $this->command = $command;
    }

    /**
     * Error the install.
     *
     * @param null|string $message
     */
    protected function errorInstall($message = null): void
    {
        $this->command->output->error($message ?: 'Installation Failed');
        die(0);
    }

    /**
     * Get the composer command for the environment.
     *
     * @return string
     */
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

        if ('\\' !== DIRECTORY_SEPARATOR && posix_isatty(STDIN)) {
            $process->setTty(true);
        }

        $process->setTimeout(null)->run(function ($type, $line) {
            $this->command->output->write($line);
        });

        if (! $process->isSuccessful()) {
            $this->errorInstall();
        }
    }
}
