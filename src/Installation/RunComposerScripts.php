<?php

namespace Aero\Cli\Installation;

use Aero\Cli\InstallStep;
use Symfony\Component\Process\Process;

class RunComposerScripts extends InstallStep
{
    /**
     * Run the installation helper.
     *
     * @return void
     */
    public function install()
    {
        mkdir($this->command->path.'/public/vendor/aerocommerce/', 0755, true);

        $composer = $this->findComposer();

        $commands = [
            $composer.' install --no-scripts --prefer-dist --no-suggest',
            $composer.' run-script post-root-package-install --quiet',
            $composer.' run-script post-create-project-cmd --quiet',
            $composer.' run-script post-autoload-dump --quiet',
        ];

        $process = new Process(implode(' && ', $commands), $this->command->path, null, null, null);

        if ('\\' !== DIRECTORY_SEPARATOR && posix_isatty(STDIN)) {
            $process->setTty(true);
        }

        $process->run(function ($type, $line) {
            $this->command->output->write($line);
        });
    }
}
