<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Student;
use App\Models\Assignment;
use App\Models\AttendanceSession;
use App\Models\Submission;
use App\Models\AttendanceRecord;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Default Admin User
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Academic Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'student@example.com'],
            [
                'name' => 'Academic Student',
                'password' => Hash::make('password'),
                'role' => 'student',
            ]
        );

        User::updateOrCreate(
            ['email' => 'dosen@example.com'],
            [
                'name' => 'Academic Lecturer',
                'password' => Hash::make('password'),
                'role' => 'dosen',
            ]
        );

        // Create 50 Fake Students for Development
        Student::factory(50)->create();

        // Create sample assignments and attendance sessions for the demo student
        $assignment1 = Assignment::firstOrCreate([
            'title' => 'Tugas Pemrograman 1',
        ], [
            'question' => 'Kerjakan soal pemrograman dasar.',
            'description' => 'Tuliskan solusi program menggunakan bahasa yang Anda pilih dan sertakan penjelasan singkat.',
            'available_from' => now()->subDays(7),
            'due_at' => now()->addDays(7),
            'created_by' => User::where('email', 'dosen@example.com')->first()?->id,
        ]);

        $assignment2 = Assignment::firstOrCreate([
            'title' => 'Tugas Basis Data',
        ], [
            'question' => 'Buat ERD dan normalisasi database sederhana.',
            'description' => 'Buat diagram ERD dan jelaskan tahapan normalisasi hingga bentuk normal ketiga.',
            'available_from' => now()->subDays(3),
            'due_at' => now()->addDays(10),
            'created_by' => User::where('email', 'dosen@example.com')->first()?->id,
        ]);

        $session1 = AttendanceSession::firstOrCreate([
            'name' => 'Absensi Pertemuan 1'
        ], [
            'open_at' => now()->subHours(2),
            'close_at' => now()->addHours(2),
            'created_by' => User::where('email', 'dosen@example.com')->first()?->id,
        ]);

        $studentUser = User::where('email', 'student@example.com')->first();
        if ($studentUser) {
            // create a submission record for assignment1
            $demoPath = 'submissions/demo-' . time() . '.txt';
            Submission::updateOrCreate([
                'assignment_id' => $assignment1->id,
                'user_id' => $studentUser->id,
            ], [
                'file_path' => $demoPath,
                'notes' => 'Contoh pengumpulan tugas oleh mahasiswa (dummy).',
                'submitted_at' => now()->subDay(),
            ]);

            // ensure demo file exists in storage/app/public
            $full = storage_path('app/public/' . $demoPath);
            if (! file_exists(dirname($full))) {
                mkdir(dirname($full), 0755, true);
            }
            if (! file_exists($full)) {
                file_put_contents($full, "Demo submission file for student\n");
            }

            // mark attendance for session1
            AttendanceRecord::updateOrCreate([
                'attendance_session_id' => $session1->id,
                'user_id' => $studentUser->id,
            ], [
                'present_at' => now()->subMinutes(30),
                'metadata' => json_encode(['method' => 'manual-demo']),
            ]);
        }
    }
}
