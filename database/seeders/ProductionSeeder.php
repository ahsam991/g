<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductionSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            BranchSeeder::class,
        ]);

        $email = 'admin@gconsultancy.co.uk';
        $existing = User::where('email', $email)->first();

        if ($existing) {
            $this->command?->warn("Admin {$email} already exists. Password left unchanged.");

            return;
        }

        // Generated once, shown once, and must be replaced at first sign-in.
        $password = Str::password(16);

        User::create([
            'name' => 'Administrator',
            'email' => $email,
            'password' => $password,
            'role_id' => Role::where('slug', Role::ADMIN)->value('id'),
            'branch_id' => Branch::where('code', 'LON')->value('id'),
            'status' => 'active',
            'must_change_password' => true,
        ])->forceFill(['email_verified_at' => now()])->save();

        $this->command?->newLine();
        $this->command?->info('Admin account created.');
        $this->command?->line("  Email:    {$email}");
        $this->command?->line("  Password: {$password}");
        $this->command?->warn('  Save this now. It is not stored anywhere and must be changed at first sign-in.');
        $this->command?->newLine();
    }
}
