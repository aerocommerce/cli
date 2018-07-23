<?php

namespace Aero\Cli;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command as SymfonyCommand;

class NewCommand extends SymfonyCommand
{
    /**
     * The input interface.
     *
     * @var InputInterface
     */
    public $input;

    /**
     * The output interface.
     *
     * @var OutputInterface
     */
    public $output;

    /**
     * The path to the new Spark installation.
     *
     * @var string
     */
    public $path;

    public $environment = [
        Development\BrewInstall::class,
        Development\InstallComposer::class,
        Development\ComposerCGRInstall::class,
        Development\InstallPHP::class,
        Development\ValetPlus::class,
        Development\InstallElasticSearch::class,
        Development\CreateProject::class,
    ];

    public $project = [
        Installation\CreateLaravelProject::class,
        Installation\AeroStructure::class,
        Installation\UpdateComposerFile::class,
        Installation\ComposerUpdate::class,
        Installation\RunAeroInstall::class,
    ];

    /**
     * Configure the command options.
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('new')
            ->setDescription('Create a new Aero Commerce application.')
            ->addArgument('name', InputArgument::REQUIRED)
            ->addOption('internal', null, InputOption::VALUE_NONE, 'Configure the project to use local Aero Commerce packages.');
    }

    /**
     * Execute the command.
     *
     * @param  InputInterface $input
     * @param  OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = new SymfonyStyle($input, $output);

        $this->path = getcwd().'/'.$input->getArgument('name');

        $installers = $this->getInstallers();

        foreach ($installers as $installer) {
            (new $installer($this, $input->getArgument('name')))->install();
        }
    }

    /**
     * @return array
     */
    protected function getInstallers()
    {
        if ($this->input->getOption('internal')) {
            return $this->environment;
        }

        return $installers = [
            Installation\CreateLaravelProject::class,
            Installation\UpdateComposerFile::class,
            Installation\ComposerUpdate::class,
            Installation\RunAeroInstall::class,
        ];
    }
}
