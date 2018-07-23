<?php

namespace Aero\Cli\Development;

use Aero\Cli\NewCommand;
use Symfony\Component\Console\Question\Question;

class CreateProject
{
    private $command;

    private $installers = [
        Project\CreateLaravelProject::class,
        Project\CreateFramework::class,
        Project\UpdateComposerFile::class,
        Project\UpdateComposer::class,
        Project\ValetLink::class,
    ];

    public function __construct(NewCommand $command)
    {
        $this->command = $command;
    }

    public function install()
    {
        $helper = $this->command->getHelper('question');
        $question = new Question('Where do you wish the Aero Project to be installed? (Default: Current Directory',
            getcwd());

        $installPath = $helper->ask($this->command->input, $this->command->output, $question);

        foreach ($this->installers as $installer) {
            (new $installer($this->command, $installPath))->install();
        }
    }
}
