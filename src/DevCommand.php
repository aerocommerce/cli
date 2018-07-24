<?php

namespace Aero\Cli;

use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command as SymfonyCommand;

class DevCommand extends SymfonyCommand
{
    protected $installers = [
        Development\BrewInstall::class,
        Development\InstallComposer::class,
        Development\ComposerCGRInstall::class,
        Development\InstallPHP::class,
        Development\ValetPlus::class,
        Development\InstallElasticSearch::class,
        Development\CreateProject::class,
    ];

    /**
     * Configure the command options.
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('dev')
            ->setDescription('Create a new Aero Commerce application.');
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

    private function getInstallers()
    {
        return $this->installers;
    }
}
