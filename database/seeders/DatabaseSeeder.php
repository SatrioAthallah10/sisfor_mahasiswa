<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {

        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Academic Admin',
                'password' => Hash::make('password'),
            ]
        );

        Student::factory(50)->create();
    }
}
