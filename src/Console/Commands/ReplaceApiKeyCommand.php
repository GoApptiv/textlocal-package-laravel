<?php

namespace GoApptiv\TextLocal\Console\Commands;

use Exception;
use GoApptiv\TextLocal\Services\TextLocal\TextLocalService;
use Illuminate\Console\Command;

class ReplaceApiKeyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'textlocal:replace-apikey';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command replaces api key in textlocal_accounts table';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(TextLocalService $textLocalService)
    {
        $accountId = $this->ask('Enter the account id');
        $apiKey = $this->ask('What is the new API Key of the account?');

        try {
            if (!$textLocalService->replaceApiKey($accountId, $apiKey)) {
                $this->error("Error while updating API Key");
            }

            $this->info('Account API Key replace successfully');
        } catch (Exception $e) {
            $this->error("Error while updating API Key");
        }
    }
}
