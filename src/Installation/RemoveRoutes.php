<?php

namespace Aero\Cli\Installation;

use Aero\Cli\InstallStep;

class RemoveRoutes extends InstallStep
{
    public function install(): void
    {
        $this->command->output->write('Removing default routes...');

        $this->removeWebRoutes();

        $this->removeApiRoutes();

        $this->command->output->writeln(' <info>✔</info>');
    }

    protected function updateFile(string $path): void
    {
        $contents = file_get_contents($path);

        $contents = preg_replace('/(^Route::.*\;)/ms', '', $contents);

        file_put_contents($path, $contents);
    }

    protected function removeWebRoutes(): void
    {
        $path = $this->command->path.'/routes/web.php';

        $this->updateFile($path);
    }

    protected function removeApiRoutes(): void
    {
        $path = $this->command->path.'/routes/api.php';

        $this->updateFile($path);
    }
}
