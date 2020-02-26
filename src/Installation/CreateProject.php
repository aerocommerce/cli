<?php

namespace Aero\Cli\Installation;

use Aero\Cli\InstallStep;

class CreateProject extends InstallStep
{
    public function install(): void
    {
        $this->command->output->write('Downloading base project...');

        $command = [
            $this->findComposer(),
            'create-project',
            'laravel/laravel=~6.0',
            $this->command->relativePath,
            '--quiet',
            '--no-scripts',
            '--no-install',
        ];

        $this->runCommand($command);

        $this->command->output->writeln(' <info>✔</info>');
    }
}
