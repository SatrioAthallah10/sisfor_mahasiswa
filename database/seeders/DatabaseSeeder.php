<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\User;
use App\Models\Course;
use App\Models\Assignment;
use App\Models\Submission;
use App\Models\AttendanceSession;
use App\Models\AttendanceRecord;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // 1. Seed Admin
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Academic Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // 2. Seed Lecturer
        $lecturerUser = User::updateOrCreate(
            ['email' => 'dosen@example.com'],
            [
                'name' => 'Dr. Gilang Dosen',
                'password' => Hash::make('password'),
                'role' => 'dosen',
            ]
        );

        // 3. Seed Demo Student User
        $studentUser = User::updateOrCreate(
            ['email' => 'student@example.com'],
            [
                'name' => 'Rizal Mahasiswa',
                'password' => Hash::make('password'),
                'role' => 'student',
            ]
        );

        // 4. Seed Demo Student Profile
        $demoStudentProfile = Student::updateOrCreate(
            ['nim' => '06.2024.1.07770'],
            [
                'name' => 'Rizal Mahasiswa',
                'prodi' => 'Teknik Informatika',
                'gpa' => 3.85,
                'user_id' => $studentUser->id,
            ]
        );

        // 5. Seed Courses
        $course1 = Course::firstOrCreate([
            'code' => 'IF-301',
        ], [
            'name' => 'Pemrograman Web',
            'lecturer_id' => $lecturerUser->id,
        ]);

        $course2 = Course::firstOrCreate([
            'code' => 'IF-202',
        ], [
            'name' => 'Basis Data',
            'lecturer_id' => $lecturerUser->id,
        ]);

        // 6. Seed other students (each gets an associated user account)
        Student::factory(25)->create()->each(function ($student) {
            $user = User::create([
                'name' => $student->name,
                'email' => strtolower(str_replace(' ', '', $student->name)) . '-' . rand(10,99) . '@example.com',
                'password' => Hash::make('password'),
                'role' => 'student',
            ]);
            $student->update(['user_id' => $user->id]);
        });

        // 7. Seed assignments linked to courses
        $assignment1 = Assignment::firstOrCreate([
            'title' => 'Tugas Pemrograman 1',
        ], [
            'question' => 'Kerjakan soal pemrograman dasar.',
            'description' => 'Tuliskan solusi program menggunakan bahasa yang Anda pilih dan sertakan penjelasan singkat.',
            'available_from' => now()->subDays(7),
            'due_at' => now()->addDays(7),
            'created_by' => $lecturerUser->id,
            'course_id' => $course1->id,
        ]);

        $assignment2 = Assignment::firstOrCreate([
            'title' => 'Tugas Basis Data',
        ], [
            'question' => 'Buat ERD dan normalisasi database sederhana.',
            'description' => 'Buat diagram ERD dan jelaskan tahapan normalisasi hingga bentuk normal ketiga.',
            'available_from' => now()->subDays(3),
            'due_at' => now()->addDays(10),
            'created_by' => $lecturerUser->id,
            'course_id' => $course2->id,
        ]);

        // 8. Seed attendance sessions linked to courses
        $session1 = AttendanceSession::firstOrCreate([
            'name' => 'Absensi Pertemuan 1'
        ], [
            'open_at' => now()->subHours(2),
            'close_at' => now()->addHours(2),
            'created_by' => $lecturerUser->id,
            'course_id' => $course1->id,
        ]);

        // 9. Create submission & attendance record for demo student
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
        if (!file_exists(dirname($full))) {
            mkdir(dirname($full), 0755, true);
        }
        if (!file_exists($full)) {
            file_put_contents($full, "Demo submission file for student\n");
        }

        AttendanceRecord::updateOrCreate([
            'attendance_session_id' => $session1->id,
            'user_id' => $studentUser->id,
        ], [
            'present_at' => now()->subMinutes(30),
            'metadata' => json_encode(['method' => 'manual-demo']),
        ]);
    }
}
