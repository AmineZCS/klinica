<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        $password = (string) env('SUPER_ADMIN_PASSWORD', '');

        User::firstOrCreate(
            ['email' => (string) env('SUPER_ADMIN_EMAIL', 'superadmin@klinica.space')],
            [
                'name' => 'Super Admin',
                'password' => Hash::make($password !== '' ? $password : Str::random(32)),
                'is_super_admin' => true,
            ]
        );
    }
}
