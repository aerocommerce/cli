<?php

namespace Aero\Cli;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

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
        Installation\RunConfigureCommand::class,
        Installation\RunInstallCommand::class,
    ];

    /**
     * Configure the command options.
     *
     * @return void
     */
    protected function configure(): void
    {
        $this->setName('new')
            ->setDescription('Create a new Aero Commerce application')
            ->addArgument('project');
    }

    /**
     * Execute the command.
     *
     * @param  InputInterface $input
     * @param  OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = new SymfonyStyle($input, $output);
        $this->project = $input->getArgument('project') ?? $this->output->askQuestion(new Question('Project directory (relative to current directory)'));
        $this->path = getcwd().'/'.$this->project;

        $this->verifyApplicationDoesntExist($this->path);

        $this->output->title('Creating a new Aero Commerce store');

        $installers = $this->getInstallers();

        foreach ($installers as $installer) {
            (new $installer($this))->install();
        }

        return 0;
    }

    /**
     * The installer steps for this command.
     *
     * @return array
     */
    protected function getInstallers(): array
    {
        return $this->installers;
    }
}
