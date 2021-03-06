<?php

namespace Aero\Cli\Installation;

use Aero\Cli\InstallStep;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\HttpClient\HttpClient;

class AddAuthFile extends InstallStep
{
    /**
     * @var string
     */
    protected $username;

    /**
     * @var string
     */
    protected $password;

    public function install(): void
    {
        while (! $this->username || ! $this->password || ! $this->checkCredentials()) {
            if (! $this->getCredentialsFromEnv()) {
                $this->promptForCredentials();
            }
        }

        $this->command->output->newLine();

        $this->writeAuthFile([
            'http-basic' => [
                'agora.aerocommerce.com' => [
                    'username' => $this->username,
                    'password' => $this->password,
                ],
            ],
        ]);
    }

    /**
     * Ask the user for the credentials for private repositories.
     *
     * @return void
     */
    protected function promptForCredentials(): void
    {
        $this->command->output->section('Please provide the package repository credentials for the project');

        $this->username = $this->command->output->askQuestion(new Question('Username'));
        $this->password = $this->command->output->askQuestion(new Question('Password'));
    }

    protected function getCredentialsFromEnv(): bool
    {
        $username = getenv('PACKAGE_REPOSITORY_USERNAME');
        $password = getenv('PACKAGE_REPOSITORY_PASSWORD');

        if ($username && $password) {
            $this->username = $username;
            $this->password = $password;

            return true;
        }

        return false;
    }

    protected function checkCredentials(): bool
    {
        $this->command->output->write('Checking credentials...');

        $client = HttpClient::create([
            'auth_basic' => [$this->username, $this->password],
        ]);
        $response = $client->request('GET', 'https://agora.aerocommerce.com/check');

        $statusCode = $response->getStatusCode();

        $authorised = $statusCode === 200;

        $this->command->output->writeln($authorised ? ' <info>✔</info>' : ' <fg=red>✘</>');

        return $authorised;
    }

    protected function writeAuthFile(array $auth): void
    {
        file_put_contents(
            $this->command->path.'/auth.json',
            json_encode($auth, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
        );
    }
}
