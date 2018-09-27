<?php

namespace Aero\Cli\Installation;

use Aero\Cli\Command;
use Aero\Cli\InstallStep;

class CreateThemeDirectory extends InstallStep
{
    /**
     * Create a new installation helper instance.
     *
     * @param \Aero\Cli\Command $command
     */
    public function __construct(Command $command)
    {
        parent::__construct($command);

        $this->command->output->write('Creating theme directory');
    }

    /**
     * Run the installation helper.
     *
     * @return void
     */
    public function install()
    {
        $parts = explode('/', $this->command->theme);

        $path = $this->command->path.'/aero/themes';

        foreach ($parts as $part) {
            $path .= '/'.$part;

            $this->makeDirectory($path);
        }

        $this->command->output->writeln(': <info>✔</info>');
    }

    /**
     * Create a directory.
     *
     * @param $path
     */
    protected function makeDirectory($path)
    {
        @mkdir($path);

        @chmod($path, octdec('0777'));
    }
}
