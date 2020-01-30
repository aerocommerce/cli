<?php

namespace Aero\Cli\Installation;

use Aero\Cli\InstallStep;

class RemoveRoutes extends InstallStep
{
    /**
     * Remove the default application routes that will conflict with Aero Commerce.
     *
     * @return void
     */
    public function install()
    {
        $this->command->output->write('Removing default routes...');

        $this->removeWebRoutes();

        $this->removeApiRoutes();

        $this->command->output->writeln(' <info>✔</info>');
    }

    /**
     * Update the file to remove the routes.
     *
     * @param $path
     */
    protected function updateFile($path): void
    {
        $contents = file_get_contents($path);

        $contents = preg_replace('/(^Route::.*\;)/ms', '', $contents);

        file_put_contents($path, $contents);
    }

    /**
     * Remove the web routes.
     */
    protected function removeWebRoutes(): void
    {
        $path = $this->command->path.'/routes/web.php';

        $this->updateFile($path);
    }

    /**
     * Remove the API routes.
     */
    protected function removeApiRoutes(): void
    {
        $path = $this->command->path.'/routes/api.php';

        $this->updateFile($path);
    }
}
