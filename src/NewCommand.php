<?php

namespace Aero\Cli;

use RuntimeException;
use GuzzleHttp\Client;
use Symfony\Component\Process\Process;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

class NewCommand extends Command
{
    use Downloader, ZipManager;

    /**
     * @var InputInterface
     */
    protected $input;

    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * @var string
     */
    protected $directory;

    /**
     * @var string
     */
    protected $version;

    /**
     * Configure the command options.
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('new')
            ->setDescription('Create a new Aero Commerce application.')
            ->addArgument('name', InputArgument::REQUIRED)
            ->addOption('force', null, InputOption::VALUE_NONE, 'Force Aero Commerce to be downloaded, even if a cached version or the directory already exists.');
    }

    /**
     * Execute the command.
     *
     * @param  InputInterface $input
     * @param  OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (! class_exists('ZipArchive')) {
            throw new RuntimeException('The Zip PHP extension is not installed. Please install it and try again.');
        }

        $this->output = $output;
        $this->input = $input;

        $this->directory = getcwd().'/'.$input->getArgument('name');

        if (! $input->getOption('force')) {
            $this->verifyApplicationDoesntExist($this->directory);
        }

        $this->version = $this->getVersion();

        $this->download($zipName = $this->makeFilename())
            ->extract($zipName)
            ->prepareWritableDirectories($this->directory)
            ->cleanup($zipName);

        $output->writeln('Configuring dependencies...');

        $composer = $this->findComposer();

        $commands = [
            $composer.' install --no-scripts',
            $composer.' run-script post-root-package-install',
            $composer.' run-script post-create-project-cmd',
            $composer.' run-script post-autoload-dump',
        ];

        $process = new Process(implode(' && ', $commands), $this->directory, null, null, null);

        if ('\\' !== DIRECTORY_SEPARATOR && file_exists('/dev/tty') && is_readable('/dev/tty')) {
            $process->setTty(true);
        }

        $process->run(function ($type, $line) use ($output) {
            $output->write($line);
        });

        $this->output->writeln(' <info>[✔]</info>');

        $this->output->writeln('<info>[✔] Aero Commerce has been installed.</info>');
    }

    /**
     * Verify that the application does not already exist.
     *
     * @param  string $directory
     * @return void
     */
    protected function verifyApplicationDoesntExist($directory)
    {
        if (is_dir($directory)) {
            throw new RuntimeException('Application already exists!');
        }
    }

    /**
     * Generate a random temporary filename.
     *
     * @return string
     */
    protected function makeFilename()
    {
        return getcwd().'/aero_'.md5(time().uniqid());
    }

    /**
     * Make sure the storage and bootstrap cache directories are writable.
     *
     * @param  string $appDirectory
     * @return $this
     */
    protected function prepareWritableDirectories($appDirectory)
    {
        $filesystem = new Filesystem;

        try {
            $filesystem->chmod($appDirectory.DIRECTORY_SEPARATOR.'bootstrap/cache', 0755, 0000, true);
            $filesystem->chmod($appDirectory.DIRECTORY_SEPARATOR.'storage', 0755, 0000, true);
        } catch (IOExceptionInterface $e) {
            $this->output->writeln('<comment>You should verify that the "storage" and "bootstrap/cache" directories are writable.</comment>');
        }

        return $this;
    }

    /**
     * Get the version that should be downloaded.
     *
     * @return string
     */
    protected function getVersion()
    {
        $this->output->write('Checking for the latest version...');

        $version = (new Client)->get('https://builder.aerocommerce.com/check')->getBody();

        $this->output->writeln(" <info>[✔] $version</info>");

        return $version;
    }

    /**
     * Get the composer command for the environment.
     *
     * @return string
     */
    protected function findComposer()
    {
        if (file_exists(getcwd().'/composer.phar')) {
            return '"'.PHP_BINARY.'" composer.phar';
        }

        return 'composer';
    }
}
