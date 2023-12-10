<?php

namespace Tamani\Students\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Tamani\Students\Models\Student;

class StudentFactory extends Factory
{
    protected $model = Student::class;

    public function definition():array
    {
        return [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'student_id' => 'HS-' . strtoupper(Str::random(10)),
            'contact_person' => $this->faker->name(),
            'contact_number' => '639457201016',
            'contact_address' => $this->faker->address(),
        ];
    }
}
