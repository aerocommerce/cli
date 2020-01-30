<?php

namespace Aero\Cli\Installation;

use Aero\Cli\InstallStep;

class RunInstallCommand extends InstallStep
{
    /**
     * Run the installation helper.
     *
     * @return void
     */
    public function install()
    {
        $command = [
            PHP_BINARY,
            "{$this->command->project}/artisan",
            'aero:install',
            '--seed',
        ];

        $this->runCommand($command);
    }
}
