<?php

namespace Aero\Cli;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

class InstallCommand extends Command
{
    protected function configure(): void
    {
        $this->setName('new')
            ->setDescription('Install a new Aero Commerce store')
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

        $output = <<<'EOL'


 █████╗ ███████╗██████╗  ██████╗ 
██╔══██╗██╔════╝██╔══██╗██╔═══██╗
███████║█████╗  ██████╔╝██║   ██║
██╔══██║██╔══╝  ██╔══██╗██║   ██║
██║  ██║███████╗██║  ██║╚██████╔╝
╚═╝  ╚═╝╚══════╝╚═╝  ╚═╝ ╚═════╝

EOL;

        $this->output->writeln($output);

        $installers = $this->getInstallers();

        $interaction = ! $this->input->getOption('no-interaction');

        foreach ($installers as $installer) {
            (new $installer($this))->setInteraction($interaction)->install();
        }

        return 0;
    }

    protected function getInstallers(): array
    {
        $installers = [
            Installation\CreateProject::class,
            Installation\RemoveRoutes::class,
            Installation\RemoveRobots::class,
            Installation\RemoveWelcomeView::class,
            Installation\RequireInstallerDependencies::class,
            Installation\RunComposerScripts::class,
            Installation\AddSetupJson::class,
            Installation\StartBackgroundWorker::class,
        ];

        return $installers;
    }
}
