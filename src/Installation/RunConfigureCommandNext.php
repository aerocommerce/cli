<?php

namespace Aero\Cli\Installation;

use Aero\Cli\InstallStep;

class RunConfigureCommandNext extends InstallStep
{
    public function install(): void
    {
        $command = [
            PHP_BINARY,
            "{$this->command->relativePath}/artisan",
            'aero:configure',
            '--no-interaction'
        ];

        $this->runCommand($command);
    }
}
