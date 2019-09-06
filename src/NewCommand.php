<?php

namespace Aero\Cli;

use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class NewCommand extends Command
{
    /**
     * The installer steps to run.
     *
     * @var array
     */
    protected $installers = [
        Installation\CreateProject::class,
        Installation\RemoveRoutes::class,
        Installation\UpdateComposerFile::class,
        Installation\AddAuthFile::class,
        Installation\RunComposerScripts::class,
    ];

    /**
     * Configure the command options.
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('new')
            ->setDescription('Create a new Aero Commerce application')
            ->addArgument('name', InputArgument::REQUIRED)
            ->addOption('docker', null, InputOption::VALUE_NONE, 'Install docker as part of the new site process');
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
        $this->project = $input->getArgument('name');
        $this->path = getcwd().'/'.$this->project;

        $this->verifyApplicationDoesntExist($this->path);

        $this->output->title('Creating a new Aero Commerce store');

        $installers = $this->getInstallers();

        if ($this->input->getOption('docker')) {
            array_push($installers, Installation\AddDocker::class);
        }

        foreach ($installers as $installer) {
            (new $installer($this))->install();
        }
    }

    /**
     * The installer steps for this command.
     *
     * @return array
     */
    protected function getInstallers()
    {
        return $this->installers;
    }
}
