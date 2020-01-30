<?php

namespace Aero\Cli\Installation;

use Aero\Cli\InstallStep;

class RunConfigureCommand extends InstallStep
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
            'aero:configure',
        ];

        $this->runCommand($command);
    }
}
