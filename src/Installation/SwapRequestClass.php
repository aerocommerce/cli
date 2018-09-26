<?php

namespace Aero\Cli\Installation;

use Aero\Cli\NewCommand;

class SwapRequestClass
{
    protected $command;

    protected $name;

    /**
     * Create a new installation helper instance.
     *
     * @param NewCommand $command
     * @param  string $name
     */
    public function __construct(NewCommand $command, $name)
    {
        $this->command = $command;
        $this->name = $name;

        $this->command->output->writeln('Installing Aero Request: <info>✔</info>');
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
    }
}
