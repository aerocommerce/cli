<?php

namespace Aero\Cli\Installation;

use Aero\Cli\InstallStep;

class UpdateDatabaseConfigFile extends InstallStep
{
    public function install(): void
    {
        $path = $this->command->path.'/config/database.php';

        if (! file_exists($path)) {
            return;
        }

        $contents = file_get_contents($path);

        if (! str_contains($contents, "'default' =>") || str_contains($contents, "'default' => env('DB_CONNECTION', 'mysql')")) {
            return;
        }

        $this->command->output->write('Setting database driver to mysql...');

        $contents = preg_replace(
            "/'default'\s*=>\s*env\('DB_CONNECTION',\s*'sqlite'\)/",
            "'default' => env('DB_CONNECTION', 'mysql')",
            $contents
        );

        file_put_contents($path, $contents);

        $this->command->output->writeln(' <info>✔</info>');
    }
}
