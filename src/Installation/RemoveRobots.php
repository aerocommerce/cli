<?php

namespace Aero\Cli\Installation;

use Aero\Cli\InstallStep;

class RemoveRobots extends InstallStep
{
    public function install(): void
    {
        $this->command->output->write('Removing robots.txt...');

        $this->removeFile($this->command->path.'/public/robots.txt');

        $this->command->output->writeln(' <info>✔</info>');
    }

    protected function removeFile(string $path): void
    {
        if (file_exists($path)) {
            unlink($path);
        }
    }
}
