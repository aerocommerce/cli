<?php

namespace Aero\Cli\Installation;

use Aero\Cli\InstallStep;

class RunConfigureCommand extends InstallStep
{
    public function install(): void
    {
        $command = [
            PHP_BINARY,
            "{$this->command->relativePath}/artisan",
            'aero:configure',
        ];

        $this->runCommand($command);
    }
}
