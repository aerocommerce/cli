<?php

namespace Aero\Cli;

interface InstallStepInterface
{
    public function __construct(Command $command);

    public function install(): void;

    public function setInteraction(bool $enabled): self;
}
