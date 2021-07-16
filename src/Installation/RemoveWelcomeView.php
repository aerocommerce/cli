<?php

namespace Aero\Cli\Installation;

use Aero\Cli\InstallStep;

class RemoveWelcomeView extends InstallStep
{
    public function install(): void
    {
        $this->command->output->write('Removing welcome.blade.php...');

        $this->removeFile($this->command->path.'/resources/views/welcome.blade.php');

        $this->command->output->writeln(' <info>✔</info>');
    }

    protected function removeFile(string $path): void
    {
        if (file_exists($path)) {
            unlink($path);
        }
    }
}
