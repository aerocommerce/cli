<?php

namespace Aero\Cli\Installation;

use Aero\Cli\Command;
use Aero\Cli\InstallStep;
use Symfony\Component\Console\Question\Question;

class AddAuthFile extends InstallStep
{
    /**
     * Create a new installation helper instance.
     *
     * @param \Aero\Cli\Command $command
     */
    public function __construct(Command $command)
    {
        parent::__construct($command);
    }

    /**
     * Run the installation helper.
     *
     * @return void
     */
    public function install()
    {
        if (!$auth = $this->getCredentialsFromEnv()) {
            $auth = $this->promptForCredentials();
        }

        $this->command->output->newLine();

        $this->writeAuthFile($auth);
    }

    /**
     * Ask the user for the credentials for private repositories.
     *
     * @return array
     */
    protected function promptForCredentials()
    {
        $this->command->output->section('Please provide your credentials for the Aero Commerce Package Repository');

        $password = new Question('Password');
        $password->setHidden(true)->setHiddenFallback(false);

        return [
            'http-basic' => [
                'packages.aerocommerce.com' => [
                    'username' => $this->command->output->askQuestion(new Question('Username')),
                    'password' => $this->command->output->askQuestion($password),
                ],
            ],
        ];
    }

    /**
     * Get the repository credentials from environment variables if possible
     *
     * @return array|bool
     */
    protected function getCredentialsFromEnv()
    {
        $this->command->output->write('Getting Aero Commerce Package Repository credentials from env...');
        $username = getenv('PACKAGE_REPOSITORY_USERNAME');
        $password = getenv('PACKAGE_REPOSITORY_PASSWORD');

        if ($username && $password) {
            $this->command->output->writeln(' <info>✔</info>');

            return [
                'http-basic' => [
                    'packages.aerocommerce.com' => [
                        'username' => $username,
                        'password' => $password,
                    ],
                ],
            ];
        }

        $this->command->output->writeln(' <fg=red>✘</>');
        return false;
    }

    /**
     * Write the auth.json file to disk.
     *
     * @param  array $auth
     * @return void
     */
    protected function writeAuthFile($auth)
    {
        file_put_contents(
            $this->command->path.'/auth.json',
            json_encode($auth, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
        );
    }
}
