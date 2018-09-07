<?php

namespace Aero\Cli;

use Symfony\Component\Process\Process;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ThemeCommand extends Command
{
    private $input;
    private $output;
    private $theme;
    private $path;

    protected function configure()
    {
        $this->setName('theme')
            ->setDescription('Create a new Aero Commerce theme.')
            ->addArgument('name', InputArgument::REQUIRED)
            ->addArgument('path', InputArgument::OPTIONAL);
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;
        $this->theme = $input->getArgument('name');
        $this->path = expand_tilde($input->getArgument('path')) ?? getcwd();

        if ($this->isAeroProject()) {
            $this->output->writeln('Creating directories for your Aero Commerce theme.');
            $this->makeThemeDirectories();
            $this->output->writeln('Generating template stubs.');
            $this->copyTemplateFiles();
            $this->output->writeln("Success! Your new \"{$this->theme}\" has been created.");
            $this->output->writeln('Do not forget to update your Aero configuration.');
        } else {
            $this->output->writeln('Specified path is not an Aero project. Please try again.');
        }
    }

    private function isAeroProject()
    {
        $composer = collect(json_decode(file_get_contents($this->path.'/composer.json')));

        if ($composer->isEmpty()) {
            return false;
        }

        return $composer->filter(function ($value, $key) {
            return $key == 'require';
        })->flatMap(function ($value) {
            return get_object_vars($value);
        })->has('aerocommerce/framework');
    }

    protected function makeThemeDirectories(): void
    {
        $process = new Process('mkdir aero; cd aero; mkdir themes; cd themes; mkdir '.$this->theme, $this->path);
        $process->run();
    }

    /**
     * @todo make and move actual stubs.
     */
    private function copyTemplateFiles()
    {
        $process = new Process('touch welcome.blade.php', $this->path.'/aero/themes/'.$this->theme);
        $process->run();
    }
}
