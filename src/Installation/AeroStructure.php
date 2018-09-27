<?php

namespace Aero\Cli\Installation;

use Aero\Cli\InstallStep;

class AeroStructure extends InstallStep
{
    /**
     * Run the installation helper.
     *
     * @return void
     */
    public function install()
    {
        $path = $this->command->path.'/aero';

        @mkdir($path);

        @chmod($path, octdec('0777'));

        if ($this->command->input->getOption('internal')) {
            @mkdir($path.'/repositories');
        }

        @mkdir($path.'/themes');

        @chmod($path.'/themes', octdec('0777'));
    }
}
