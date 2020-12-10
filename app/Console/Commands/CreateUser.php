<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:user {email} {password} {name?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '创建用户';

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
     * @return mixed
     */
    public function handle()
    {
        $email    = $this->argument('email');
        $password = $this->argument('password');
        $name     = $this->argument('name');

        if ($email && $password) {
            User::create([
                'name'     => $name ?? $email,
                'email'    => $email,
                'password' => Hash::make($password),
            ]);
        }
    }
}
