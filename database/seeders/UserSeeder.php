<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole    = Role::where('slug', 'admin')->first();
        $pengurusRole = Role::where('slug', 'pengurus')->first();

        // Password acak dibuat baru setiap kali seeder dijalankan — TIDAK di-hardcode
        $adminPassword    = Str::password(14);
        $pengurusPassword = Str::password(14);

        $adminCreated = false;
        $adminUser = User::where('email', 'admin@karangtaruna.com')->first();
        if (!$adminUser) {
            User::create([
                'name'      => 'Super Admin',
                'email'     => 'admin@karangtaruna.com',
                'password'  => Hash::make($adminPassword),
                'role_id'   => $adminRole ? $adminRole->id : 1,
                'phone'     => '081234567890',
                'is_active' => true,
            ]);
            $adminCreated = true;
        }

        $pengurusCreated = false;
        $pengurusUser = User::where('email', 'pengurus@karangtaruna.com')->first();
        if (!$pengurusUser) {
            User::create([
                'name'      => 'Budi Pengurus',
                'email'     => 'pengurus@karangtaruna.com',
                'password'  => Hash::make($pengurusPassword),
                'role_id'   => $pengurusRole ? $pengurusRole->id : 2,
                'phone'     => '082345678901',
                'is_active' => true,
            ]);
            $pengurusCreated = true;
        }

        if ($adminCreated || $pengurusCreated) {
            $this->command->newLine();
            $this->command->warn('=== CATAT PASSWORD INI SEKARANG, TIDAK AKAN TAMPIL LAGI ===');
            if ($adminCreated) {
                $this->command->line("Super Admin  -> admin@karangtaruna.com     / {$adminPassword}");
            }
            if ($pengurusCreated) {
                $this->command->line("Pengurus     -> pengurus@karangtaruna.com  / {$pengurusPassword}");
            }
            $this->command->warn('Segera login dan ganti password ini lewat menu profil setelah deploy.');
            $this->command->newLine();
        }
    }
}
