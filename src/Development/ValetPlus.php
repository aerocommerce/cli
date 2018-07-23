<?php

namespace Aero\Cli\Development;

use Aero\Cli\NewCommand;
use Symfony\Component\Process\Process;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class ValetPlus
{
    private $command;

    public function __construct(NewCommand $command)
    {
        $this->command = $command;
    }

    /**
     * Install Valet Plus.
     */
    public function install()
    {
        $this->command->output->writeln('<info>Valet Plus Installer</info>');

        if (! $this->globalWeProvidePackages()->contains('valet-plus')) {
            $this->installValetPlus();
        }
    }

    /**
     * Get a collection of gloabally installed packages.
     *
     * @return \Illuminate\Support\Collection
     */
    private function globalWeProvidePackages()
    {
        $process = new Process('ls ~/.composer/global/test');
        $process->run();

        if ($process->isSuccessful()) {
            return collect(explode("\n", $process->getOutput()));
        }

        return collect();
    }

    /**
     * Install Valet Plus.
     */
    private function installValetPlus()
    {
        $this->command->output->writeln('<info>Valet Plus was not detected on your system and is the recommended development environment for Aero Commerce.</info>');

        $helper = $this->command->getHelper('question');
        $question = new ConfirmationQuestion('Install Valet Plus? (Y/N)', false);

        if ($helper->ask($this->command->input, $this->command->output, $question)) {
            $this->removeLaravelValet();

            $this->composerRequireValetPlus();

            $this->valetFix();

            $this->valetInstall();
        }
    }

    /**
     * Composer require Valet Plus.
     */
    private function composerRequireValetPlus()
    {
        $valet = new Process('cgr weprovide/valet-plus');
        $valet->run(function ($type, $line) {
            $this->command->output->write($line);
        });
    }

    /**
     * Run valet fix.
     */
    private function valetFix()
    {
        $fix = new Process('valet fix');
        $fix->run(function ($type, $line) {
            $this->command->output->write($line);
        });
    }

    /**
     * Run Valet Install.
     */
    private function valetInstall()
    {
        $install = new Process('valet install');
        $install->setTimeout(3600);
        $install->run(function ($type, $line) {
            $this->command->output->write($line);
        });
    }

    /**
     * Uninstall Laravel Valet.
     */
    private function removeLaravelValet()
    {
        $this->command->output->writeln('<warning>Removing Larvel Valet</warning>');

        $uninstall = new Process('valet uninstall');
        $uninstall->run(function ($type, $line) {
            $this->command->output->write($line);
        });

        $remove = new Process('composer global remove laravel/valet');
        $remove->run(function ($type, $line) {
            $this->command->output->write($line);
        });
    }
}
