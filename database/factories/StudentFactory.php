<?php

namespace Database\Factories;

use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    protected $model = Student::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $prodis = [
            'Sistem Informasi',
            'Teknik Informatika',
            'Sains Data',
            'Teknologi Informasi',
            'Rekayasa Perangkat Lunak'
        ];

        return [
            'nim' => 'SIS-' . $this->faker->unique()->numerify('#####'),
            'name' => $this->faker->name(),
            'prodi' => $this->faker->randomElement($prodis),
            'gpa' => $this->faker->randomFloat(2, 2.00, 4.00),
            'photo_path' => null,
        ];
    }
}
