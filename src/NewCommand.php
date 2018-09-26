<?php

namespace Aero\Cli;

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

    public $installers = [
        Installation\CreateLaravelProject::class,
        Installation\AeroStructure::class,
        Installation\UpdateComposerFile::class,
        Installation\ComposerUpdate::class,
        Installation\InstallProviders::class,
        Installation\RemoveProviders::class,
        Installation\RemoveRoutes::class,
        Installation\SwapRequestClass::class,
        // Installation\RunAeroInstall::class,
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
            ->addOption('internal', InputArgument::OPTIONAL)
            ->addArgument('name', InputArgument::REQUIRED);
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
        return $this->installers;
    }
}
