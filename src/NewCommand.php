<?php

namespace Aero\Cli;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class NewCommand extends Command
{
    protected $installers = [
        Installation\CreateProject::class,
        Installation\RemoveRoutes::class,
        Installation\UpdateComposerFile::class,
        Installation\AddAuthFile::class,
        Installation\RunComposerScripts::class,
        Installation\RunConfigureCommand::class,
        Installation\RunInstallCommand::class,
    ];

    protected function configure(): void
    {
        $this->setName('new')
            ->setDescription('Create a new Aero Commerce project')
            ->addArgument('project', InputArgument::OPTIONAL);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->input = $input;
        $this->output = new SymfonyStyle($input, $output);
        $this->project = $input->getArgument('project');

        $cwd = getcwd();

        $this->relativePath = $this->project;

        if (! $this->project) {
            $this->project = basename($cwd);
            $cwd = dirname($cwd);

            $this->relativePath = '.';
        }

        $this->path = $cwd.'/'.$this->project;

        $this->verifyApplicationDoesntExist($this->path);

        $this->output->title('Creating a new Aero Commerce project');

        $installers = $this->getInstallers();

        foreach ($installers as $installer) {
            $step = new $installer($this);
            /** @var \Aero\Cli\InstallStepInterface $step */
            $step->install();
        }

        return 0;
    }

    protected function getInstallers(): array
    {
        return $this->installers;
    }
}
