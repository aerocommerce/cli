<?php

namespace Aero\Cli\Installation;

use Aero\Cli\InstallStep;
use Aero\Cli\Command;

class SwapThemeEnv extends InstallStep
{
    /**
     * Create a new installation helper instance.
     *
     * @param \Aero\Cli\Command $command
     */
    public function __construct(Command $command)
    {
        parent::__construct($command);

        $this->command->output->write('Installing theme');
    }

    /**
     * Install the service providers.
     *
     * @return void
     */
    public function install()
    {
        $path = $this->command->path.'/.env';

        if (file_exists($path)) {
            $contents = file_get_contents($path);

            if (strpos($contents, 'AERO_THEME=') !== false) {
                $contents = preg_replace(
                    "/AERO_THEME=(.*)$/m",
                    $this->command->theme,
                    $contents
                );
            } else {
                $contents .= "\nAERO_THEME={$this->command->theme}\n";
            }

            file_put_contents($path, $contents);
        }

        $this->command->output->writeln(': <info>✔</info>');
    }
}
