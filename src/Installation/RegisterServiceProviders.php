<?php

namespace Aero\Cli\Installation;

use Aero\Cli\NewCommand;

class RegisterServiceProviders
{
    protected $command;

    /**
     * Create a new installation helper instance.
     *
     * @param  NewCommand $command
     * @return void
     */
    public function __construct(NewCommand $command)
    {
        $this->command = $command;
    }

    /**
     * Run the installation helper.
     *
     * @return void
     */
    public function install()
    {
        $providers = [];

        if ($this->command->input->getOption('internal')) {
            foreach (['core'] as $repository) {
                $composer = $this->getComposerConfiguration($repository);

                if (isset($composer['extra']['laravel']['providers'])) {
                    $providers = array_merge($providers, $composer['extra']['laravel']['providers']);
                }
            }

            $providers = array_unique($providers);
        }

        $path = $this->command->path.'/config/app.php';

        $contents = file_get_contents($path);

        $contents = str_replace(
            '        App\\Providers\\AppServiceProvider::class,',
            '        '.implode("::class,\n        ", $providers)."::class,\n        App\Providers\AppServiceProvider::class,",
            $contents
        );

        file_put_contents($path, $contents);
    }

    /**
     * Read the Composer file from disk.
     *
     * @param  string $repository
     * @return array
     */
    protected function getComposerConfiguration($repository)
    {
        return json_decode(file_get_contents($this->command->path.'/aero/repositories/'.$repository.'/composer.json'), true);
    }
}
