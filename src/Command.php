<?php

namespace Aero\Cli;

use FilesystemIterator;
use RuntimeException;
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
     * @var \Symfony\Component\Console\Style\SymfonyStyle
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
     * The relative path to the project.
     *
     * @var string
     */
    public $relativePath;

    protected function verifyApplicationDoesntExist(string $directory): void
    {
        if (is_file($directory) || (is_dir($directory) && (new FilesystemIterator($directory))->valid())) {
            throw new RuntimeException('Application already exists!');
        }
    }
}
