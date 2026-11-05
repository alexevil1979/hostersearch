<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'superadmin@hostersearch.test'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'role' => UserRole::SuperAdmin,
                'email_verified_at' => now(),
                'locale' => 'en',
            ]
        );

        User::query()->updateOrCreate(
            ['email' => 'admin@hostersearch.test'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'role' => UserRole::Admin,
                'email_verified_at' => now(),
                'locale' => 'en',
            ]
        );

        User::query()->updateOrCreate(
            ['email' => 'partner@hostersearch.test'],
            [
                'name' => 'Partner Manager',
                'password' => Hash::make('password'),
                'role' => UserRole::Partner,
                'email_verified_at' => now(),
                'locale' => 'en',
            ]
        );

        User::query()->updateOrCreate(
            ['email' => 'user@hostersearch.test'],
            [
                'name' => 'Demo User',
                'password' => Hash::make('password'),
                'role' => UserRole::User,
                'email_verified_at' => now(),
                'locale' => 'ru',
            ]
        );

        $reviewers = [
            ['name' => 'Alex Morgan', 'email' => 'alex@example.com'],
            ['name' => 'Maria Petrova', 'email' => 'maria@example.com'],
            ['name' => 'James Chen', 'email' => 'james@example.com'],
            ['name' => 'Sofia Rossi', 'email' => 'sofia@example.com'],
            ['name' => 'Omar Hassan', 'email' => 'omar@example.com'],
        ];

        foreach ($reviewers as $reviewer) {
            User::query()->updateOrCreate(
                ['email' => $reviewer['email']],
                [
                    'name' => $reviewer['name'],
                    'password' => Hash::make('password'),
                    'role' => UserRole::User,
                    'email_verified_at' => now(),
                ]
            );
        }
    }
}
