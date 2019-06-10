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

        $command->output->section('Please provide your credentials for the Aero Commerce Package Repository');
    }

    /**
     * Run the installation helper.
     *
     * @return void
     */
    public function install()
    {
        $auth = $this->promptForCredentials();

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
