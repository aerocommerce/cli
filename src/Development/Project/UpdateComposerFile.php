<?php

namespace Aero\Cli\Development\Project;

use Aero\Cli\DevCommand;

class UpdateComposerFile
{
    private $command;
    private $path;

    /**
     * Create a new installation helper instance.
     *
     * @param  DevCommand $command
     * @param             $path
     */
    public function __construct(DevCommand $command, $path)
    {
        $this->path = expand_tilde($path);
        $this->command = $command;
    }

    /**
     * Run the installation helper.
     *
     * @return void
     */
    public function install()
    {
        $composer = $this->getComposerConfiguration();

        $composer = $this->addDependencies($composer);

        $composer = $this->addInternalRepositories($composer);

        $this->writeComposerFile($composer);
    }

    /**
     * Read the Composer file from disk.
     *
     * @return array
     */
    protected function getComposerConfiguration()
    {
        return json_decode(file_get_contents($this->path.'/aero/composer.json'), true);
    }

    /**
     * Add the Composer dependencies required for an Aero Commerce Store.
     *
     * @param  array $composer
     * @return array
     */
    protected function addDependencies($composer)
    {
        $composer['require']['aerocommerce/framework'] = '*';

        return $composer;
    }

    /**
     * Add the internal repositories to the Composer array.
     *
     * @param  array $composer
     * @return array
     */
    protected function addInternalRepositories($composer)
    {
        $composer['require']['aerocommerce/framework'] = '*';

        $composer['repositories'][] = [
            'type' => 'path',
            'url' => $this->path . '/framework',
        ];

        return $composer;
    }

    /**
     * Write the given Composer configuration back to disk.
     *
     * @param  array $composer
     * @return void
     */
    protected function writeComposerFile($composer)
    {
        file_put_contents($this->path.'/aero/composer.json', json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }
}
