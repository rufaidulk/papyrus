<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Profile;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = $this->getTempUsers();

        foreach ($users as $role => $user)
        {
            Role::create(['name' => $role]);
            $user = User::create($user);
            $user->assignRole($role);
            Profile::create(['user_id' => $user->id]);
        }
    }

    private function getTempUsers()
    {
        return [
            'admin' => [
                'name' => 'admin',
                'email' => 'admin@test.com',
                'email_verified_at' => now(),
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'remember_token' => Str::random(10),
                'status' => User::STATUS_ACTIVE
            ],
            'writer' => [
                'name' => 'ruskin bond',
                'email' => 'ruskinbond@test.com',
                'email_verified_at' => now(),
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'remember_token' => Str::random(10),
                'status' => User::STATUS_ACTIVE
            ],
            'writer' => [
                'name' => 'kumaranasan',
                'email' => 'kumaranasan@test.com',
                'email_verified_at' => now(),
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'remember_token' => Str::random(10),
                'status' => User::STATUS_ACTIVE
            ],
            'reader' => [
                'name' => 'alex',
                'email' => 'alex@test.com',
                'email_verified_at' => now(),
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'remember_token' => Str::random(10),
                'status' => User::STATUS_ACTIVE
            ],
            'reader' => [
                'name' => 'thomas',
                'email' => 'thomas@test.com',
                'email_verified_at' => now(),
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'remember_token' => Str::random(10),
                'status' => User::STATUS_ACTIVE
            ]
        ];
    }
}
