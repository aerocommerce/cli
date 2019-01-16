<?php

namespace Aero\Cli;

use Symfony\Component\Console\Command\Command as SymfonyCommand;

abstract class Command extends SymfonyCommand
{
    /**
     * The input interface.
     *
     * @var \Symfony\Component\Console\Input\InputInterface
     */
    public $input;

    /**
     * The output interface.
     *
     * @var \Symfony\Component\Console\Output\OutputInterface
     */
    public $output;

    /**
     * The name of the project.
     *
     * @var string
     */
    public $project;

    /**
     * The path to the project.
     *
     * @var string
     */
    public $path;

    /**
     * @var bool
     */
    private $isAeroProject;

    /**
     * Verify that the application does not already exist.
     *
     * @param  string  $directory
     * @return void
     */
    protected function verifyApplicationDoesntExist($directory)
    {
        if (is_dir($directory) || is_file($directory)) {
            throw new \RuntimeException('Application already exists!');
        }
    }

    /**
     * Verify that the application is an Aero project.
     *
     * @return void
     */
    protected function verifyIsAeroProject()
    {
        if (! $this->determineAeroProject()) {
            throw new \RuntimeException('Application is not an Aero Commerce project!');
        }
    }

    /**
     * Is the project an Aero project.
     *
     * @return bool
     */
    protected function isAeroProject()
    {
        if (is_null($this->isAeroProject)) {
            $this->isAeroProject = $this->determineAeroProject();
        }

        return $this->isAeroProject;
    }

    /**
     * Determine if the path is an Aero Commerce project.
     *
     * @return bool
     */
    private function determineAeroProject()
    {
        $file = $this->path.'/composer.json';

        if (! file_exists($file)) {
            return false;
        }

        $composer = collect(json_decode(file_get_contents($file)));

        if ($composer->isEmpty()) {
            return false;
        }

        return $composer->filter(function ($value, $key) {
            return $key == 'require';
        })->flatMap(function ($value) {
            return get_object_vars($value);
        })->has('aerocommerce/core');
    }
}
