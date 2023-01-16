<?php

namespace Devdot\UserArtisan\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;

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
        try {
            $users = User::all([
                'id',
                'name',
                'email',
                ])->toArray();
        }
        catch(\Illuminate\Database\QueryException $e) {
            // check if the table exists
            if(Schema::hasTable('users') == false) {
                $this->error('Missing user table! Run php artisan migrate to create default user table.');
                return Command::FAILURE;
            }

            // it's not the expected source of error, throw again
            throw $e;
        }

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
