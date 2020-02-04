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
        'aerocommerce/admin' => '^0',
        'aerocommerce/core' => '^0',
        'aerocommerce/checkout' => '^0',
        'aerocommerce/default-theme' => '^0',
    ];

    /**
     * The scripts to run.
     *
     * @var array
     */
    protected $scripts = [
        'post-autoload-dump' => [
            '@php artisan aero:link --ansi',
        ],
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

        $composer = $this->addScripts($composer);

        $this->writeComposerFile($composer);
    }

    /**
     * Read the Composer file from disk.
     *
     * @return array
     */
    protected function getComposerConfiguration(): array
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
    protected function addDependencies($composer): array
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
    protected function addRepository($composer): array
    {
        if (! isset($composer['repositories'])) {
            $composer['repositories'] = [];
        }

        $composer['repositories'][] = [
            'type' => 'composer',
            'url' => 'https://agora.aerocommerce.com',
        ];

        return $composer;
    }

    /**
     * Add scripts for an Aero Commerce Store.
     *
     * @param  array $composer
     * @return array
     */
    protected function addScripts($composer): array
    {
        if (! isset($composer['scripts'])) {
            $composer['scripts'] = [];
        }

        foreach ($this->scripts as $area => $scripts) {
            if (! isset($composer['scripts'][$area])) {
                $composer['scripts'][$area] = [];
            }

            foreach ($scripts as $script) {
                $composer['scripts'][$area][] = $script;
            }
        }

        return $composer;
    }

    /**
     * Write the given Composer configuration back to disk.
     *
     * @param  array $composer
     * @return void
     */
    protected function writeComposerFile($composer): void
    {
        file_put_contents(
            $this->command->path.'/composer.json',
            json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
        );
    }
}
