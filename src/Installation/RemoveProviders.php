<?php

namespace Aero\Cli\Installation;

use Aero\Cli\Command;
use Aero\Cli\InstallStep;

class RemoveProviders extends InstallStep
{
    /**
     * Create a new installation helper instance.
     *
     * @param \Aero\Cli\Command $command
     */
    public function __construct(Command $command)
    {
        parent::__construct($command);

        $this->command->output->write('Removing Service Provider');
    }

    /**
     * Remove the service providers.
     *
     * @return void
     */
    public function install()
    {
        $path = $this->command->path.'/config/app.php';

        $contents = file_get_contents($path);

        $contents = str_replace(
            '        App\\Providers\\RouteServiceProvider::class,',
            '        // App\\Providers\\RouteServiceProvider::class,',
            $contents
        );

        file_put_contents($path, $contents);

        $this->command->output->writeln(': <info>✔</info>');
    }
}
