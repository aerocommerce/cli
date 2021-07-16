<?php

namespace Aero\Cli\Installation;

use Aero\Cli\InstallStep;

class StartBackgroundWorker extends InstallStep
{
    public function install(): void
    {
        $command = [
            PHP_BINARY,
            "artisan",
            'aero:setup:worker',
            '--no-interaction',
        ];

        $this->runCommand($command, $this->command->relativePath, true);
    }
}
