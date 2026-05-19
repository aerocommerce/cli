<?php

namespace Aero\Cli\Installation;

use Aero\Cli\InstallStep;

class UpdateSessionConfigFile extends InstallStep
{
    public function install(): void
    {
        $path = $this->command->path.'/config/session.php';

        if (! file_exists($path)) {
            return;
        }

        $contents = file_get_contents($path);

        if (! str_contains($contents, "'serialization' =>") || str_contains($contents, "'serialization' => 'php'")) {
            return;
        }

        $this->command->output->write('Setting serialization to php in config/session.php...');

        $contents = preg_replace(
            "/'serialization'\s*=>\s*'json',/",
            "'serialization' => 'php',",
            $contents
        );

        file_put_contents($path, $contents);

        $this->command->output->writeln(' <info>✔</info>');
    }
}
