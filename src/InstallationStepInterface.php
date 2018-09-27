<?php

namespace Aero\Cli;

interface InstallationStepInterface
{
    /**
     * Create a new installation step instance.
     *
     * @param \Aero\Cli\Command $command
     */
    public function __construct(Command $command);

    /**
     * Run the step.
     *
     * @return mixed
     */
    public function install();
}
