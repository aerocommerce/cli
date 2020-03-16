<?php

namespace Aero\Cli\Installation;

use Aero\Cli\InstallStep;

class UpdateComposerFile extends InstallStep
{
    protected $dependencies = [
        'aerocommerce/admin' => '^0',
        'aerocommerce/core' => '^0',
        'aerocommerce/elastic-search' => '^0',
        'aerocommerce/checkout' => '^0',
        'aerocommerce/default-theme' => '^0',
    ];

    protected $scripts = [
        'post-autoload-dump' => [
            '@php artisan aero:link --ansi',
        ],
    ];

    public function install(): void
    {
        $composer = $this->getComposerConfiguration();

        $composer = $this->addRepository($this->addDependencies($composer));

        $composer = $this->addScripts($composer);

        $this->writeComposerFile($composer);
    }

    protected function getComposerConfiguration(): array
    {
        return json_decode(file_get_contents(
            $this->command->path.'/composer.json'
        ), true);
    }

    protected function addDependencies(array $composer): array
    {
        foreach ($this->dependencies as $dependency => $version) {
            $composer['require'][$dependency] = $version;
        }

        return $composer;
    }

    protected function addRepository(array $composer): array
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

    protected function addScripts(array $composer): array
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

    protected function writeComposerFile(array $composer): void
    {
        file_put_contents(
            $this->command->path.'/composer.json',
            json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
        );
    }
}
