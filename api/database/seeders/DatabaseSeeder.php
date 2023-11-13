<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Tamani\Admin\Models\Admin;
use Tamani\Admin\Seeders\AdminAccountsSeeder;
use Tamani\Students\Seeders\StudentSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Admin::factory()->create([
            'email' => 'admin@attendancesystem.internal'
        ]);
        $this->call(AdminAccountsSeeder::class);
        $this->call(StudentSeeder::class);
    }
}
