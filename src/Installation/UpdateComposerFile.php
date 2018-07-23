<?php

namespace Aero\Cli\Installation;

use Aero\Cli\NewCommand;
use Symfony\Component\Console\Question\Question;

class UpdateComposerFile
{
    protected $command;

    protected $name;

    /**
     * Create a new installation helper instance.
     *
     * @param  NewCommand $command
     * @param  string $name
     * @return void
     */
    public function __construct(NewCommand $command, $name)
    {
        $this->name = $name;
        $this->command = $command;
    }

    /**
     * Run the installation helper.
     *
     * @return void
     */
    public function install()
    {
        $composer = $this->getComposerConfiguration();

        $composer = $this->addDependencies($composer);

        if ($this->command->input->getOption('internal')) {
            $composer = $this->addInternalRepositories($this->addInternalDependencies($composer));
        }

        $this->writeComposerFile($composer);
    }

    /**
     * Read the Composer file from disk.
     *
     * @return array
     */
    protected function getComposerConfiguration()
    {
        return json_decode(file_get_contents($this->command->path.'/composer.json'), true);
    }

    /**
     * Add the Composer dependencies required for an Aero Commerce Store.
     *
     * @param  array $composer
     * @return array
     */
    protected function addDependencies($composer)
    {
        $composer['require']['aerocommerce/core'] = 'dev-master';

        return $composer;
    }

    /**
     * Add the Composer dependencies required for an Aero Commerce Store when developing internally.
     *
     * @param  array $composer
     * @return array
     */
    protected function addInternalDependencies($composer)
    {
        $composer['require']['aerocommerce/framework'] = 'dev-master';

        return $composer;
    }

    /**
     * Add the internal repositories to the Composer array.
     *
     * @param  array $composer
     * @return array
     */
    protected function addInternalRepositories($composer)
    {
        $helper = $this->command->getHelper('question');

        $question = new Question('Please enter the path to aerocommerce/core: ');
        $question->setValidator(function ($answer) {
            if (! is_dir($answer)) {
                throw new \RuntimeException('The path does not exist.');
            }

            return $answer;
        });

        $corePath = $this->command->path.'/aero/repositories/core';

        symlink($helper->ask($this->command->input, $this->command->output, $question), $corePath);

        $composer['require']['aerocommerce/core'] = 'dev-master';

        $composer['repositories'][] = [
            'type' => 'path',
            'url' => $corePath,
        ];

        return $composer;
    }

    /**
     * Write the given Composer configuration back to disk.
     *
     * @param  array $composer
     * @return void
     */
    protected function writeComposerFile($composer)
    {
        file_put_contents($this->command->path.'/composer.json', json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }
}
