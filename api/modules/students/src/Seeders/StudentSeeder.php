<?php


namespace Tamani\Students\Seeders;

use Illuminate\Database\Seeder;
use Tamani\Students\Models\Student;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        Student::factory(1000)->create();
    }
}
