<?php

namespace Aero\Cli\Installation;

use Aero\Cli\InstallStep;
use Symfony\Component\Process\Process;

class RunComposerScripts extends InstallStep
{
    public function install(): void
    {
        $composer = $this->findComposer();

        $commands = [
            $composer.' install --no-scripts --prefer-dist --no-suggest',
            $composer.' run-script post-root-package-install --quiet',
            $composer.' run-script post-create-project-cmd --quiet',
            $composer.' run-script post-autoload-dump --quiet',
        ];

        $process = Process::fromShellCommandline(implode(' && ', $commands), $this->command->path, null, null, null);

        if ('\\' !== DIRECTORY_SEPARATOR && file_exists('/dev/tty') && is_readable('/dev/tty')) {
            $process->setTty(true);
        }

        $process->run(function ($_, $line) {
            $this->command->output->write($line);
        });
    }
}
