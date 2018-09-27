<?php

namespace Aero\Cli;

abstract class InstallStep implements InstallationStepInterface
{
    /**
     * @var \Aero\Cli\Command
     */
    protected $command;

    /**
     * Create a new installation helper instance.
     *
     * @param \Aero\Cli\Command $command
     */
    public function __construct(Command $command)
    {
        $this->command = $command;
    }
}
