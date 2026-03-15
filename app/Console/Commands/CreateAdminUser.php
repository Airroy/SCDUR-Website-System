<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create 
                            {--email= : Admin email address}
                            {--name= : Admin name}
                            {--password= : Admin password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new admin user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== Create Admin User ===');
        $this->newLine();

        // Get input from options or prompt
        $email = $this->option('email') ?: $this->ask('Email address', 'admin@aru.ac.th');
        $name = $this->option('name') ?: $this->ask('Name', 'Admin');
        $password = $this->option('password') ?: $this->secret('Password (leave empty for "1234")') ?: '1234';

        // Validate input
        $validator = Validator::make([
            'email' => $email,
            'name' => $name,
            'password' => $password,
        ], [
            'email' => 'required|email',
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:4',
        ]);

        if ($validator->fails()) {
            $this->error('Validation failed:');
            foreach ($validator->errors()->all() as $error) {
                $this->error('  - ' . $error);
            }
            return 1;
        }

        // Check if user exists
        if (User::where('email', $email)->exists()) {
            if (!$this->confirm("User with email '{$email}' already exists. Update password?", true)) {
                $this->info('Cancelled.');
                return 0;
            }

            // Update existing user
            $user = User::where('email', $email)->first();
            $user->update([
                'name' => $name,
                'password' => Hash::make($password),
                'email_verified_at' => now(),
            ]);

            $this->newLine();
            $this->info('✓ Admin user updated successfully!');
        } else {
            // Create new user
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
                'email_verified_at' => now(),
            ]);

            $this->newLine();
            $this->info('✓ Admin user created successfully!');
        }

        // Display credentials
        $this->newLine();
        $this->table(
            ['Field', 'Value'],
            [
                ['Email', $email],
                ['Name', $name],
                ['Password', '********'],
            ]
        );

        $this->newLine();
        $this->info('You can now login at: ' . config('app.url') . '/aru-scdur-panel');

        return 0;
    }
}
