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

    /**
     * Run the installation helper.
     *
     * @return void
     */
    public function install()
    {
        while (! $this->username || ! $this->password || ! $this->checkCredentials()) {
            if (! $this->getCredentialsFromEnv()) {
                $this->promptForCredentials();
            }
        }

        $this->command->output->newLine();

        $this->writeAuthFile([
            'http-basic' => [
                'packages.aerocommerce.com' => [
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
        $this->command->output->section('Please provide your credentials for the Aero Commerce Package Repository');

        $password = new Question('Password');
        $password->setHidden(true)->setHiddenFallback(false);

        $this->username = $this->command->output->askQuestion(new Question('Username'));
        $this->password = $this->command->output->askQuestion($password);
    }

    /**
     * Get the repository credentials from environment variables if possible.
     *
     * @return bool
     */
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

    /**
     * Check the credentials are correct.
     *
     * @return bool
     */
    protected function checkCredentials(): bool
    {
        $this->command->output->write('Checking credentials...');

        $client = HttpClient::create([
            'auth_basic' => [$this->username, $this->password],
        ]);
        $response = $client->request('GET', 'https://packages.aerocommerce.com/check');

        $statusCode = $response->getStatusCode();

        $authorised = $statusCode === 200;

        $this->command->output->writeln($authorised ? ' <info>✔</info>' : ' <fg=red>✘</>');

        return $authorised;
    }

    /**
     * Write the auth.json file to disk.
     *
     * @param  array $auth
     * @return void
     */
    protected function writeAuthFile($auth): void
    {
        file_put_contents(
            $this->command->path.'/auth.json',
            json_encode($auth, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
        );
    }
}
