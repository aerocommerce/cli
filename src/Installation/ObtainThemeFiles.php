<?php

namespace Aero\Cli\Installation;

use Aero\Cli\InstallStep;
use Symfony\Component\Console\Question\Question;

class ObtainThemeFiles extends InstallStep
{
    /**
     * Run the installation helper.
     *
     * @return void
     */
    public function install()
    {
        if ($this->command->input->getOption('internal')) {
            $this->linkToRepository();
        }
    }

    /**
     * Install the theme files as a symlink to another directory.
     *
     * @return void
     */
    protected function linkToRepository()
    {
        $helper = $this->command->getHelper('question');

        $question = new Question("Please enter the path to {$this->command->theme}: ");
        $question->setValidator(function ($answer) {
            $answer = expand_tilde($answer);

            if (! is_dir($answer)) {
                throw new \RuntimeException('The path does not exist.');
            }

            return $answer;
        });

        $path = $this->command->path.'/aero/themes/'.$this->command->theme;

        @rmdir($path);

        symlink($helper->ask($this->command->input, $this->command->output, $question), $path);
    }
}
