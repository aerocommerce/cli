<?php

namespace Aero\Cli\Installation;

use Aero\Cli\InstallStep;

class UpdateComposerFile extends InstallStep
{
    /**
     * The dependencies required.
     *
     * @var array
     */
    protected $dependencies = [
        'aerocommerce/core' => 'dev-master',
    ];

    /**
     * Run the installation helper.
     *
     * @return void
     */
    public function install()
    {
        $composer = $this->getComposerConfiguration();

        $composer = $this->addRepository($this->addDependencies($composer));

        $this->writeComposerFile($composer);
    }

    /**
     * Read the Composer file from disk.
     *
     * @return array
     */
    protected function getComposerConfiguration()
    {
        return json_decode(file_get_contents(
            $this->command->path.'/composer.json'
        ), true);
    }

    /**
     * Add the Composer dependencies required for an Aero Commerce Store.
     *
     * @param  array $composer
     * @return array
     */
    protected function addDependencies($composer)
    {
        foreach ($this->dependencies as $dependency => $version) {
            $composer['require'][$dependency] = $version;
        }

        return $composer;
    }

    /**
     * Add the Aero Commerce package repository to the Composer array.
     *
     * @param  array  $composer
     * @return array
     */
    protected function addRepository($composer)
    {
        if (! isset($composer['repositories'])) {
            $composer['repositories'] = [];
        }

        $composer['repositories'][] = [
            'type' => 'composer',
            'url' => 'http://packages.aerocommerce.com',
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
        file_put_contents(
            $this->command->path.'/composer.json',
            json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
        );
    }
}
