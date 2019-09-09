<?php

namespace Aero\Cli\Installation;

use Aero\Cli\InstallStep;
use Symfony\Component\Process\Process;

class AddDocker extends InstallStep
{
    /**
     * Run the installation helper.
     *
     * @return void
     */
    public function install()
    {
        $this->command->output->write('Setting up Docker...');

        $composer = $this->findComposer();

        $commands = [
            "{$composer} require aerocommerce/docker-environment --prefer-dist",
            '"'.PHP_BINARY.'" artisan vendor:publish --provider="Aero\DockerEnvironmentServiceProvider"',
        ];

        $process = new Process(implode(' && ', $commands), $this->command->path);

        if ('\\' !== DIRECTORY_SEPARATOR && posix_isatty(STDIN)) {
            $process->setTty(true);
        }

        $process->run(function ($type, $line) {
            $this->command->output->write($line);
        });
    }
}
