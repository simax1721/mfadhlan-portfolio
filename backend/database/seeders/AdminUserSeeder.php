<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AdminUserSeeder extends Seeder
{
    /**
     * Seeds a single Filament admin user. Credentials can be pinned via
     * ADMIN_EMAIL / ADMIN_PASSWORD in .env; otherwise a random password
     * is generated and printed once — change it after first login.
     */
    public function run(): void
    {
        $email = env('ADMIN_EMAIL', 'mfadhlan1721@gmail.com');
        $password = env('ADMIN_PASSWORD');
        $generated = $password === null;
        $password ??= Str::password(14);

        $user = User::updateOrCreate(
            ['email' => $email],
            [
                'name' => 'M. Fadhlan',
                'password' => bcrypt($password),
                'email_verified_at' => now(),
            ],
        );

        if ($generated) {
            $this->command?->warn("Admin user ready — email: {$user->email} / password: {$password}");
            $this->command?->warn('Save this password now — it will not be shown again. Change it via Filament after logging in, or set ADMIN_PASSWORD in .env before reseeding.');
        } else {
            $this->command?->info("Admin user ready — email: {$user->email} (password from .env)");
        }
    }
}
