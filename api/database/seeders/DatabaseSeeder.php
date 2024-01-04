<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Tamani\Admin\Models\Admin;
use Tamani\Admin\Seeders\AdminAccountsSeeder;
use Tamani\Curriculum\Models\EducationLevel;
use Tamani\Curriculum\Models\Section;
use Tamani\Students\Models\Student;
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

        $level = new EducationLevel([
            'education_level_name' => 'Grade 7'
        ]);

        $level->save();

        $level->sections()->saveMany([
            new Section([
                'section_name' => 'A'
            ]),
            new Section([
                'section_name' => 'B'
            ]),
            new Section([
                'section_name' => 'C'
            ]),
            new Section([
                'section_name' => 'D'
            ]),
            new Section([
                'section_name' => 'E'
            ]),
            new Section([
                'section_name' => 'F'
            ]),
        ]);

        $level->save();

        $level = new EducationLevel([
            'education_level_name' => 'Grade 8'
        ]);

        $level->save();

        $level->sections()->saveMany([
            new Section([
                'section_name' => 'A'
            ]),
            new Section([
                'section_name' => 'B'
            ]),
            new Section([
                'section_name' => 'C'
            ]),
            new Section([
                'section_name' => 'D'
            ]),
            new Section([
                'section_name' => 'E'
            ]),
            new Section([
                'section_name' => 'F'
            ]),
        ]);

        $level = new EducationLevel([
            'education_level_name' => 'Grade 9'
        ]);

        $level->save();

        $level->sections()->saveMany([
            new Section([
                'section_name' => 'A'
            ]),
            new Section([
                'section_name' => 'B'
            ]),
            new Section([
                'section_name' => 'C'
            ]),
            new Section([
                'section_name' => 'D'
            ]),
            new Section([
                'section_name' => 'E'
            ]),
            new Section([
                'section_name' => 'F'
            ]),
        ]);

        $level = new EducationLevel([
            'education_level_name' => 'Grade 10'
        ]);

        $level->save();

        $level->sections()->saveMany([
            new Section([
                'section_name' => 'A'
            ]),
            new Section([
                'section_name' => 'B'
            ]),
            new Section([
                'section_name' => 'C'
            ]),
            new Section([
                'section_name' => 'D'
            ]),
            new Section([
                'section_name' => 'E'
            ]),
        ]);

        $sections = Section::get();

        /** @var Section $section */
        foreach ($sections as $section){
            Student::factory(60)->create([
                'section_id' => $section->id,
                'education_level_id' => $section->educationLevel->id
            ]);
        }
    }
}
