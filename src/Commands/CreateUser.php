<?php

namespace Devdot\UserArtisan\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class CreateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create 
        {name? : The name of the new user}
        {email? : The email for the new user}
        {password? : The password to be set for the new user}
        {--hide-password : Always hide the password in output}
        {--show-password : Always show the password in output}
        ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new user in the database. Call without parameters for interactive user creation.';

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

        // see if we should do this interactively
        $interactive = $this->argument('user') == null;
        $user = new User();

        // insert data from input
        $user->name = $this->argument('name') ?? $this->anticipate('User Name?', ['John Doe'], 'John Doe');
        $user->email = $this->argument('email') ?? $this->anticipate('User Email?', ['mail@example.com'], 'mail@example.com');

        // check for the email to be unique
        if(User::where('email', $user->email)->first()) {
            $this->error('Cannot create user with this email!');
            return Command::FAILURE;
        }
        
        // check for password
        $hasPasswordInput = $this->argument('password') != null;
        if($interactive) {
            // ask whether it should be random or not
            $hasPasswordInput = $this->confirm('Generate random password?', true) == false;
        }
        $passwordClearText = '';
        if($hasPasswordInput) {
            $passwordClearText = $this->argument('password') ?? $this->secret('User Password?');
        }
        else {
            // create a random password
            $this->line('Generating random password');
            $passwordClearText = substr(base64_encode(md5(rand())), 0, 16);
        }

        // write the password and save the user
        $user->password = Hash::make($passwordClearText);
        $user->save();
        $this->info('User created successfully!');

        // output the data
        $tableHeader = [
            'ID',
            'Name',
            'Email',
        ];
        $tableData = [
            $user->id,
            $user->name,
            $user->email,
        ];
        if($this->option('hide-password') == false && ($hasPasswordInput == false || $this->option('show-password'))) {
            $tableHeader[] = 'Password';
            $tableData[] = $passwordClearText;
        }
        $this->table($tableHeader, [$tableData]);
        
        return Command::SUCCESS;
    }
}
