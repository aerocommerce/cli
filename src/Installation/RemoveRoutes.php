<?php

namespace Aero\Cli\Installation;

use Aero\Cli\InstallStep;
use Aero\Cli\Command;

class RemoveRoutes extends InstallStep
{
    /**
     * Create a new installation helper instance.
     *
     * @param \Aero\Cli\Command $command
     */
    public function __construct(Command $command)
    {
        parent::__construct($command);

        $this->command->output->write('Removing Default Routes');
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

    /**
     * Update the file to remove the routes.
     *
     * @param $path
     */
    protected function updateFile($path)
    {
        $contents = file_get_contents($path);

        $contents = preg_replace(
            '/(^Route::.*\;)/ms',
            "// To enable custom routes for your application, enable App\Providers\RouteServiceProvider in config/app.php",
            $contents
        );

        file_put_contents($path, $contents);

        $this->command->output->writeln(': <info>✔</info>');
    }

    /**
     * Remove the web routes.
     */
    protected function removeWebRoutes()
    {
        $path = $this->command->path.'/routes/web.php';

        $this->updateFile($path);
    }

    /**
     * Remove the API routes.
     */
    protected function removeApiRoutes()
    {
        $path = $this->command->path.'/routes/api.php';

        $this->updateFile($path);
    }
}
