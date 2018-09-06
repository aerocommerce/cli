<?php

namespace Aero\Cli\Installation;

use Aero\Cli\NewCommand;

class InstallProviders
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

        $this->command->output->writeln('Installing Service Providers: <info>✔</info>');
    }

    /**
     * Install the components.
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
    }
}
