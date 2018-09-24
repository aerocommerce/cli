<?php

namespace Aero\Cli\Installation;

use Aero\Cli\NewCommand;

class RemoveRoutes
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

        $this->command->output->writeln('Removing Default Routes: <info>✔</info>');
    }

    /**
     * Remove the default application routes that will conflict with Aero Commerce.
     *
     * @return void
     */
    public function install()
    {
        $this->removeWebRoutes();

        $this->removeApiRoutes();
    }

    protected function updateFile($path)
    {
        $contents = file_get_contents($path);

        $contents = preg_replace(
            '/(^Route::.*\;)/ms',
            "// To enable custom routes for your application, enable the App\Providers\RouteServiceProvider in config/app.php",
            $contents
        );

        file_put_contents($path, $contents);
    }

    protected function removeWebRoutes()
    {
        $path = $this->command->path.'/routes/web.php';

        $this->updateFile($path);
    }
    protected function removeApiRoutes()
    {
        $path = $this->command->path.'/routes/api.php';

        $this->updateFile($path);
    }
}
