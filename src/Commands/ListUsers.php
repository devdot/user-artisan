<?php

namespace Devdot\UserArtisan\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class ListUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all users in the database';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // get all users from DB using the App's models
        $users = User::all([
            'id',
            'name',
            'email',
            ])->toArray();

        // send a warning if we don't even have users
        if(count($users) == 0) {
            $this->warn('No users in database!');
        }
        else {
            // show the table
            $this->table([
                'ID',
                'Name',
                'Email',
            ], $users);
        }

        return Command::SUCCESS;
    }
}
