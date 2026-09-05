<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateAdmin extends Command
{
    protected $signature = 'admin:create {email?} {--name=Administrator}';

    protected $description = 'Create or update a secure administrator account';

    public function handle(): int
    {
        $email = $this->argument('email') ?: $this->ask('Administrator email');
        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error('A valid email is required.');

            return self::FAILURE;
        }
        $password = $this->secret('Password (minimum 12 characters)');
        $confirmation = $this->secret('Confirm password');
        if (strlen((string) $password) < 12 || ! hash_equals((string) $password, (string) $confirmation)) {
            $this->error('Passwords must match and contain at least 12 characters.');

            return self::FAILURE;
        }
        User::updateOrCreate(['email' => $email], ['name' => $this->option('name'), 'password' => Hash::make($password), 'is_admin' => true]);
        $this->info('Administrator account is ready.');

        return self::SUCCESS;
    }
}
