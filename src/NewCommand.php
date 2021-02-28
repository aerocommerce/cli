<?php

namespace Aero\Cli;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

class NewCommand extends Command
{
    protected $installers = [
        Installation\CreateProject::class,
        Installation\RemoveRoutes::class,
        Installation\RemoveRobots::class,
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
            ->addArgument('project', InputArgument::OPTIONAL, 'The name of the project');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->input = $input;
        $this->output = new SymfonyStyle($input, $output);
        $this->project = $input->getArgument('project');

        if (! $this->project) {
            $this->project = $this->output->askQuestion(new Question('Project directory (relative to current directory)'));

            if (! $this->project) {
                $this->project = '.';
            }
        }

        $cwd = getcwd();

        $this->relativePath = $this->project;

        if ($this->project === '.') {
            $this->project = basename($cwd);
            $cwd = dirname($cwd);
        }

        $this->path = $cwd.'/'.$this->project;

        $this->project = basename($this->project);

        $this->verifyApplicationDoesntExist($this->path);

        $this->output->title('Creating a new Aero Commerce project');

        $installers = $this->getInstallers();

        $interaction = ! $this->input->getOption('no-interaction');

        foreach ($installers as $installer) {
            (new $installer($this))->setInteraction($interaction)->install();
        }

        return 0;
    }

    protected function getInstallers(): array
    {
        return $this->installers;
    }
}
