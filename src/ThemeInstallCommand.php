<?php

namespace Aero\Cli;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ThemeInstallCommand extends Command
{
    /**
     * The theme name.
     *
     * @var string
     */
    public $theme;

    /**
     * The installer steps to run.
     *
     * @var array
     */
    protected $installers = [
        Installation\AeroStructure::class,
        Installation\CreateThemeDirectory::class,
        Installation\ObtainThemeFiles::class,
        Installation\SwapThemeEnv::class,
    ];

    /**
     * Configure the command options.
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('theme:install')
            ->setDescription('Install an Aero Commerce theme.')
            ->addOption('internal', InputArgument::OPTIONAL)
            ->addArgument('name', InputArgument::REQUIRED);
    }

    /**
     * Execute the command.
     *
     * @param  InputInterface $input
     * @param  OutputInterface $output
     * @return void
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;
        $this->theme = $input->getArgument('name');
        $this->path = getcwd();
        $this->project = basename($this->path);

        $this->verifyIsAeroProject();

        $installers = $this->getInstallers();

        foreach ($installers as $installer) {
            (new $installer($this))->install();
        }
    }

    /**
     * The installer steps for this command.
     *
     * @return array
     */
    protected function getInstallers()
    {
        return $this->installers;
    }
}
