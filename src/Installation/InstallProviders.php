<?php

namespace Aero\Cli\Installation;

use Aero\Cli\InstallStep;
use Aero\Cli\Command;

class InstallProviders extends InstallStep
{
    /**
     * Create a new installation helper instance.
     *
     * @param \Aero\Cli\Command $command
     */
    public function __construct(Command $command)
    {
        parent::__construct($command);

        $this->command->output->write('Installing Service Providers');
    }

    /**
     * Install the service providers.
     *
     * @return void
     */
    public function install()
    {
        $path = $this->command->path.'/config/app.php';

        $contents = file_get_contents($path);

        $contents = str_replace(
            '        App\\Providers\\AppServiceProvider::class,',
            "        Aero\Core\Providers\AeroCoreServiceProvider::class,\n        App\Providers\AppServiceProvider::class,",
            $contents
        );

        file_put_contents($path, $contents);

        $this->command->output->writeln(': <info>✔</info>');
    }
}
