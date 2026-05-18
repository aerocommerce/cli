<?php

namespace Aero\Cli\Installation;

use Aero\Cli\InstallStep;
use Aero\Cli\NewCommand;

class CreateProject extends InstallStep
{
    public function install(): void
    {
        $this->command->output->write('Downloading base project...');

        $laravel = $this->command->input->getOption('laravel') ?: NewCommand::DEFAULT_LARAVEL_VERSION;

        if (is_numeric($laravel)) {
            $laravel = "~{$laravel}.0";
        }

        $command = [
            $this->findComposer(),
            'create-project',
            "laravel/laravel={$laravel}",
            $this->command->relativePath,
            '--quiet',
            '--no-scripts',
            '--no-install',
        ];

        $this->runCommand($command);

        $this->command->output->writeln(' <info>✔</info>');
    }
}
