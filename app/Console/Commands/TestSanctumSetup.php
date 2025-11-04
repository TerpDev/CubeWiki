<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class TestSanctumSetup extends Command
{
    protected $signature = 'sanctum:test {email?}';

    protected $description = 'Test Sanctum token creation for a user';

    public function handle()
    {
        $email = $this->argument('email');

        if (!$email) {
            $user = User::first();
        } else {
            $user = User::where('email', $email)->first();
        }

        if (!$user) {
            $this->error('User not found!');
            return 1;
        }

        $this->info("Testing Sanctum for user: {$user->email}");
        $this->newLine();

        // Check if HasApiTokens trait is used
        $traits = class_uses_recursive(get_class($user));
        $hasTrait = isset($traits['Laravel\Sanctum\HasApiTokens']);

        $this->line('✓ HasApiTokens trait: ' . ($hasTrait ? '✅ YES' : '❌ NO'));

        // Check current tokens
        $tokenCount = $user->tokens()->count();
        $this->line("✓ Current token count: {$tokenCount}");

        // Create a test token
        $this->info('Creating a new test token...');
        $token = $user->createToken('test-cli-token');

        $this->newLine();
        $this->line('✅ Token created successfully!');
        $this->line("Token ID: {$token->accessToken->id}");
        $this->line("Token (plain text): {$token->plainTextToken}");

        $this->newLine();
        $this->info('You can test this token with:');
        $this->line("curl -H 'Authorization: Bearer {$token->plainTextToken}' " . url('/api/user'));

        $this->newLine();
        if ($this->confirm('Delete this test token?', true)) {
            $token->accessToken->delete();
            $this->info('Test token deleted.');
        }

        return 0;
    }
}

