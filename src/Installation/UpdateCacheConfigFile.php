<?php

namespace Aero\Cli\Installation;

use Aero\Cli\InstallStep;

class UpdateCacheConfigFile extends InstallStep
{
    public function install(): void
    {
        $path = $this->command->path.'/config/cache.php';

        if (! file_exists($path)) {
            return;
        }

        $contents = file_get_contents($path);

        if (! str_contains($contents, "'serializable_classes' =>") || str_contains($contents, "'serializable_classes' => true")) {
            return;
        }

        $this->command->output->write('Setting cache serializable_classes to true...');

        $contents = preg_replace(
            "/'serializable_classes'\s*=>\s*false,/",
            "'serializable_classes' => true,",
            $contents
        );

        file_put_contents($path, $contents);

        $this->command->output->writeln(' <info>✔</info>');
    }
}
