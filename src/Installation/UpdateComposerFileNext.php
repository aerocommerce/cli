<?php

namespace Aero\Cli\Installation;

class UpdateComposerFileNext extends UpdateComposerFile
{
    protected $dependencies = [
        'aerocommerce/account-area' => '^0',
        'aerocommerce/admin' => '^1',
        'aerocommerce/checkout' => '^0',
        'aerocommerce/core' => '^0',
        'aerocommerce/elastic-search' => '^1',
    ];

    protected $devDependencies = [
        'aerocommerce/dev' => '^1',
    ];
}
