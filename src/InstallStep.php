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

    /**
     * @return list<string>
     */
    protected function composerCommand(): array
    {
        $directory = $this->command->installerWorkingDirectory ?: getcwd();
        $localPhar = $directory.DIRECTORY_SEPARATOR.'composer.phar';

        if (is_file($localPhar)) {
            return [PHP_BINARY, $localPhar];
        }

        return ['composer'];
    }

    protected function findComposer(): string
    {
        return $this->escapeCommandLine($this->composerCommand());
    }

    protected function runCommand(array $command, ?string $cwd = null): void
    {
        $process = new Process($command, $cwd, null, null, null);

        $this->configureInteractiveProcess($process);

        $process->setTimeout(null)->run(function ($_, $line) {
            $this->command->output->write($line);
        });

        if (! $process->isSuccessful()) {
            $this->errorInstall();
        }
    }

    protected function runInteractiveCommand(array $command, ?string $cwd = null): void
    {
        if ($this->shouldRunInForeground()) {
            $this->runCommandInForeground($command, $cwd);

            return;
        }

        if ($this->interaction
            && '\\' === DIRECTORY_SEPARATOR
            && ! in_array('--no-interaction', $command, true)) {
            $command[] = '--no-interaction';
        }

        $this->runCommand($command, $cwd);
    }

    protected function shouldRunInForeground(): bool
    {
        return $this->interaction
            && '\\' === DIRECTORY_SEPARATOR
            && defined('STDIN')
            && stream_isatty(STDIN);
    }

    protected function runCommandInForeground(array $command, ?string $cwd = null): void
    {
        $previousDirectory = getcwd();

        if ($cwd !== null && is_dir($cwd)) {
            chdir($cwd);
        }

        passthru($this->escapeCommandLine($command), $exitCode);

        if ($previousDirectory !== false) {
            chdir($previousDirectory);
        }

        if ($exitCode !== 0) {
            $this->errorInstall();
        }
    }

    protected function escapeCommandLine(array $command): string
    {
        return implode(' ', array_map('escapeshellarg', $command));
    }

    protected function configureInteractiveProcess(Process $process): void
    {
        if (! $this->interaction || '\\' === DIRECTORY_SEPARATOR) {
            return;
        }

        if (! file_exists('/dev/tty') || ! is_readable('/dev/tty')) {
            return;
        }

        try {
            $process->setTty(true);
        } catch (RuntimeException $e) {
            $this->command->output->writeln('Warning: '.$e->getMessage());
        }
    }
}
