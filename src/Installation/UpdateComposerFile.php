<?php

namespace Aero\Cli\Installation;

use Aero\Cli\InstallStep;
use Symfony\Component\Console\Question\Question;

class UpdateComposerFile extends InstallStep
{
    /**
     * The dependencies required.
     *
     * @var array
     */
    protected $dependencies = [
        'aerocommerce/framework' => 'dev-master',
    ];

    /**
     * The development dependencies required.
     *
     * @var array
     */
    protected $internalDependencies = [];

    /**
     * The internal repository mapping when developing internally.
     *
     * @var array
     */
    protected $repositories = [
        'aerocommerce/framework' => 'framework',
    ];

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
        foreach ($this->dependencies as $dependency => $version) {
            $composer['require'][$dependency] = $version;
        }

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
        foreach ($this->internalDependencies as $dependency => $version) {
            $composer['require-dev'][$dependency] = $version;
        }

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
        foreach ($this->repositories as $repository => $path) {
            $composer = $this->addInternalRepository($composer, $repository, $path);
        }

        return $composer;
    }

    /**
     * Add an internal repository to the Composer array.
     *
     * @param $composer
     * @param $repository
     * @param $path
     * @return mixed
     */
    protected function addInternalRepository($composer, $repository, $path)
    {
        $helper = $this->command->getHelper('question');

        $default = realpath($this->command->path."/../{$path}");

        $text = "Please enter the path to {$repository}";

        if (is_dir($default)) {
            $text .= " [{$default}]";
        } else {
            $default = null;
        }

        $question = new Question($text.': ', $default);
        $question->setValidator(function ($answer) {
            $answer = expand_tilde($answer);

            if (! is_dir($answer)) {
                throw new \RuntimeException('The path does not exist.');
            }

            return $answer;
        });

        $path = $this->command->path.'/aero/repositories/'.$path;

        symlink($helper->ask($this->command->input, $this->command->output, $question), $path);

        $composer['require'][$repository] = '*';

        $composer['repositories'][] = [
            'type' => 'path',
            'url' => $path,
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
