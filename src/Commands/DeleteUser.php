<?php

namespace Devdot\UserArtisan\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;

class DeleteUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:delete 
        {user : The ID or email of the user}
        {--f|force : Force delete the user}
        ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete a given user from the database';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // make sure we have a normal users table
        if(Schema::hasTable('users') == false) {
            $this->error('Missing user table! Run php artisan migrate to create default user table.');
            return Command::FAILURE;
        }

        // check whether they passed the ID or the email
        $user = null;
        if(is_numeric($this->argument('user'))) {
            // load the user from id
            $user = User::find($this->argument('user'));
            // check if its null
            if(!$user) {
                $this->error('Could not find user with ID '.$this->argument('user'));
                return Command::FAILURE;
            }
        }
        else {
            // load the user from email
            $user = User::where('email', $this->argument('user'))->first();
            // and make sure we have the user loaded
            if(!$user) {
                $this->error('Could not find user with email '.$this->argument('user'));
                return Command::FAILURE;
            }
        }

        // now remove the user
        $user->delete();
        $this->info('The user #'.$user->id.' '.$user->email.' was deleted successfully!');

        return Command::SUCCESS;
    }
}
