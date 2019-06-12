<?php

namespace Aero\Cli;

abstract class InstallStep implements InstallationStepInterface
{
    /**
     * The command instance.
     *
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

    /**
     * Error the install.
     *
     * @param null|string $message
     */
    protected function errorInstall($message = null)
    {
        $this->command->output->error($message ?: 'Installation Failed');
        die(0);
    }

    /**
     * Get the composer command for the environment.
     *
     * @return string
     */
    protected function findComposer()
    {
        $composerPath = getcwd().'/composer.phar';

        if (file_exists($composerPath)) {
            return '"'.PHP_BINARY.'" '.$composerPath;
        }

        return 'composer';
    }
}
