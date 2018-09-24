<?php

namespace Aero\Cli\Installation;

use Aero\Cli\NewCommand;

class AeroStructure
{
    protected $command;

    /**
     * Create a new installation helper instance.
     *
     * @param  NewCommand $command
     * @return void
     */
    public function __construct(NewCommand $command)
    {
        $this->command = $command;
    }

    /**
     * Run the installation helper.
     *
     * @return void
     */
    public function install()
    {
        $path = $this->command->path.'/aero';

        @mkdir($path);

        if ($this->command->input->getOption('internal')) {
            mkdir($path.'/repositories');
        }
    }
}
