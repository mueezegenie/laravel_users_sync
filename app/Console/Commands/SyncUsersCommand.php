<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Console\Command;

class SyncUsersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:sync-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
    public function handle(UserService $userService)
    {
        $users = [];
        $page = 1;
        do {
            echo "Fetching Page $page from Api \n\r";

            $users = $userService->getUsers($page);

            foreach ($users as $user) {
                $email = "$user->first_name.$user->last_name@exmaple.com";

                $existing = User::where("email", $email)->first();

                if (!$existing) {
                    $record = [
                        "email" => "$user->first_name.$user->last_name@exmaple.com",
                        "name" => "$user->first_name $user->last_name",
                        "first_name" => $user->first_name,
                        "last_name" => $user->last_name,
                        "address" => $user->address,
                        "job_title" => $user->job_title,
                        "password" => bcrypt("1234567890")
                    ];

                    User::create($record);
                    echo "User created: $email \n\r";
                }

                $existing->first_name = $user->first_name;
                $existing->last_name = $user->last_name;
                $existing->name = "$user->first_name $user->last_name";
                $existing->address = $user->address;
                $existing->job_title = $user->job_title;

                $existing->save();

                echo "User updated: $email \n\r";

            }

            $page++;
        } while (count($users) > 0);

        echo "User Sync Finished!";

        return 0;
    }
}
