<?php

namespace Aero\Cli\Installation;

use Aero\Cli\InstallStep;

class RunInstallCommand extends InstallStep
{
    public function install(): void
    {
        $command = [
            PHP_BINARY,
            "{$this->command->relativePath}/artisan",
            'aero:install',
            '--seed',
        ];

        $this->runCommand($command);
    }
}
