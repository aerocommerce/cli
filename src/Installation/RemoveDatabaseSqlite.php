<?php

namespace Aero\Cli\Installation;

use Aero\Cli\InstallStep;

class RemoveDatabaseSqlite extends InstallStep
{
    public function install(): void
    {
        $path = $this->command->path.'/database/database.sqlite';

        if (! file_exists($path)) {
            return;
        }

        $this->command->output->write('Removing database.sqlite...');

        unlink($path);

        $this->command->output->writeln(' <info>✔</info>');
    }
}
