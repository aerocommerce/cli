<?php

namespace Aero\Cli\Installation;

use Aero\Cli\InstallStep;
use Symfony\Component\Process\Process;

class CreateProject extends InstallStep
{
    /**
     * Run the installation helper.
     *
     * @return void
     */
    public function install()
    {
        $this->command->output->write('Downloading base project...');

        // $options = ['--quiet', '--no-scripts', '--no-install'];

        // $project = escapeshellarg($this->command->project);

        // $command = "{$this->findComposer()} create-project laravel/laravel=6.0 {$project} {$options}";

        $command = [$this->findComposer(), 'create-project', 'laravel/laravel=6.0', $this->command->project, '--quiet', '--no-scripts', '--no-install'];

        $process = new Process($command, null, null, null, null);

        if ('\\' !== DIRECTORY_SEPARATOR && posix_isatty(STDIN)) {
            $process->setTty(true);
        }

        $process->setTimeout(null)->run(function ($type, $line) {
            $this->command->output->write($line);
        });

        if (! $process->isSuccessful()) {
            $this->errorInstall();
        }

        $this->command->output->writeln(' <info>✔</info>');
    }
}
