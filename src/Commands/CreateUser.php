<?php

namespace Devdot\UserArtisan\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class DeleteUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create 
        {name : The name of the new user}
        {email : The email for the new user}
        {password? : The password to be set for the new user}
        ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new user in the database';

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

        // insert data from input
        $user = new User();
        $user->name = $this->argument('name');
        $user->email = $this->argument('email');
        
        // check for password
        $hasPasswordInput = $this->hasArgument('password');
        $passwordClearText = '';
        if($hasPasswordInput) {
            $passwordClearText = $this->argument('password');
        }
        else {
            // create a random password
            $passwordClearText = substr(base64_encode(md5(rand())), 0, 16);
        }

        // write the password and save the user
        $user->password = Hash::make($passwordClearText);
        $user->save();

        // output the data
        $this->table([
            'ID',
            'Name',
            'Email',
            $hasPasswordInput ? 'Password' : null,
        ], [
            $user->id,
            $user->name,
            $user->email,
            $hasPasswordInput ? $passwordClearText : null,
        ]);

        return Command::SUCCESS;
    }
}