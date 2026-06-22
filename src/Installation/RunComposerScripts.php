<?php

namespace Aero\Cli\Installation;

use Aero\Cli\InstallStep;
use Symfony\Component\Process\Process;

class RunComposerScripts extends InstallStep
{
    public function install(): void
    {
        $commands = [
            array_merge($this->composerCommand(), ['update', '--no-scripts', '--prefer-dist']),
            array_merge($this->composerCommand(), ['run-script', 'post-root-package-install', '--quiet']),
            array_merge($this->composerCommand(), ['run-script', 'post-create-project-cmd', '--quiet']),
            array_merge($this->composerCommand(), ['run-script', 'post-autoload-dump', '--quiet']),
        ];

        foreach ($commands as $command) {
            $process = new Process($command, $this->command->path, null, null, null);

            $process->setTimeout(null)->run(function ($_, $line) {
                $this->command->output->write($line);
            });

            if (! $process->isSuccessful()) {
                $this->errorInstall();
            }
        }
    }
}
