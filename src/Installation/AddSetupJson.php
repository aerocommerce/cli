<?php

namespace Aero\Cli\Installation;

use Aero\Cli\InstallStep;

class AddSetupJson extends InstallStep
{
    public function install(): void
    {
        $file = $this->command->path.'/storage/app/setup.json';

        if (file_exists(dirname($file))) {
            file_put_contents($file, json_encode([], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        }
    }
}
