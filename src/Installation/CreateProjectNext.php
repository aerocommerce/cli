<?php

namespace Aero\Cli\Installation;

use Aero\Cli\InstallStep;

class CreateProjectNext extends InstallStep
{
    public function install(): void
    {
        $this->command->output->write('Downloading base project...');

        $command = [
            $this->findComposer(),
            'create-project',
            'laravel/laravel=~9.0',
            $this->command->relativePath,
            '--quiet',
            '--no-scripts',
            '--no-install',
        ];

        $this->runCommand($command);

        $this->command->output->writeln(' <info>✔</info>');
    }
}
