<?php

namespace Aero\Cli\Installation;

use Aero\Cli\InstallStep;

class CreateProjectNext extends InstallStep
{
    public function install(): void
    {
        $this->command->output->write('Downloading base project...');

        $laravel = $this->command->input->getOption('laravel') ?: '9';

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
