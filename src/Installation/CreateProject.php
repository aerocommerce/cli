<?php

namespace Aero\Cli\Installation;

use Aero\Cli\InstallStep;
use Aero\Cli\NewCommand;

class CreateProject extends InstallStep
{
    public function install(): void
    {
        $this->command->output->write('Downloading base project...');

        $laravel = $this->command->input->getOption('laravel') ?: '13';

        if (is_numeric($laravel)) {
            $laravel = "~{$laravel}.0";
        }

        $command = array_merge($this->composerCommand(), [
            'create-project',
            "laravel/laravel={$laravel}",
            $this->command->relativePath,
            '--quiet',
            '--no-scripts',
            '--no-install',
        ]);

        $this->runCommand($command);

        $this->command->output->writeln(' <info>✔</info>');
    }
}
