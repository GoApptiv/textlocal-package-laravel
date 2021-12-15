<?php

namespace GoApptiv\TextLocal\Console\Commands;

use GoApptiv\TextLocal\Services\TextLocal\TextLocalService;
use Illuminate\Console\Command;

class AddAccountCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'textlocal:add-account';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command adds account in the textlocal_accounts table';

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
        $name = $this->ask('What is the name of the account?');
        $email = $this->ask('What is the email id of the account?');
        $apiKey = $this->ask('What is the API Key of the account?');

        $accountId = $textLocalService->addAccount($email, $apiKey, $name);

        $this->info('Account added successfully with id: ' . $accountId);
    }
}
