<?php

namespace Aero\Cli\Installation;

use Aero\Cli\Command;
use Aero\Cli\InstallStep;

class SwapRequestClass extends InstallStep
{
    /**
     * Create a new installation helper instance.
     *
     * @param \Aero\Cli\Command $command
     */
    public function __construct(Command $command)
    {
        parent::__construct($command);

        $this->command->output->write('Installing Aero Request');
    }

    /**
     * Install the service providers.
     *
     * @return void
     */
    public function install()
    {
        $path = $this->command->path.'/public/index.php';

        $contents = file_get_contents($path);

        $contents = str_replace(
            'Illuminate\\Http\\Request::',
            'Aero\\Core\\Http\\Request::',
            $contents
        );

        file_put_contents($path, $contents);

        $this->command->output->writeln(': <info>✔</info>');
    }
}
