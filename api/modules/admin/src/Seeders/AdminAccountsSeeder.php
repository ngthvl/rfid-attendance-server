<?php

namespace Tamani\Admin\Seeders;

use Illuminate\Database\Seeder;
use Tamani\Admin\Models\Admin;

class AdminAccountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::factory(10)->create();
    }
}
