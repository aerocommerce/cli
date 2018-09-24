<?php

namespace Aero\Cli\Installation;

use Aero\Cli\NewCommand;

class RemoveProviders
{
    protected $command;

    protected $name;

    /**
     * Create a new installation helper instance.
     *
     * @param NewCommand $command
     * @param  string    $name
     */
    public function __construct(NewCommand $command, $name)
    {
        $this->command = $command;
        $this->name = $name;

        $this->command->output->writeln('Removing Service Providers: <info>✔</info>');
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
            "        // App\\Providers\\RouteServiceProvider::class,",
            $contents
        );

        file_put_contents($path, $contents);
    }
}
