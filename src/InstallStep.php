<?php

namespace Aero\Cli;

use RuntimeException;
use Symfony\Component\Process\Process;

abstract class InstallStep implements InstallStepInterface
{
    /**
     * @var \Aero\Cli\Command
     */
    protected $command;

    protected $interaction = true;

    public function __construct(Command $command)
    {
        $this->command = $command;
    }

    public function setInteraction(bool $enabled): InstallStepInterface
    {
        $this->interaction = $enabled;

        return $this;
    }

    protected function errorInstall(?string $message = null): void
    {
        $this->command->output->error($message ?: 'Installation Failed');

        exit(1);
    }

    protected function findComposer(): string
    {
        $composerPath = getcwd().'/composer.phar';

        if (file_exists($composerPath)) {
            return '"'.PHP_BINARY.'" '.$composerPath;
        }

        return 'composer';
    }

    protected function runCommand(array $command, string $cwd = null, bool $async = false): void
    {
        if ($async) {
            $command[] = '> /dev/null 2>&1 &';

            $process = Process::fromShellCommandline(implode(' ', $command), $cwd, null, null, null)->disableOutput();
        } else {
            $process = new Process($command, $cwd, null, null, null);
        }

        if ($this->interaction
            && '\\' !== DIRECTORY_SEPARATOR && file_exists('/dev/tty') && is_readable('/dev/tty')) {
            try {
                $process->setTty(true);
            } catch (RuntimeException $e) {
                $this->command->output->writeln('Warning: '.$e->getMessage());
            }
        }

        $process->setTimeout(null)->run(function ($_, $line) {
            $this->command->output->write($line);
        });

        if (! $process->isSuccessful()) {
            $this->errorInstall($process->getErrorOutput());
        }
    }
}
