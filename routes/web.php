<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

// Temporary debug route to show current authenticated user
Route::get('/me', function () {
    $user = auth()->user();
    if (! $user) {
        return response('guest', 200);
    }

    return response($user->email . '|' . $user->role, 200);
})->middleware('auth');

Route::get('/lang/{locale}', [LocaleController::class, 'switch'])->name('lang.switch');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('/dashboard', function () {
    $user = auth()->user();

    if ($user && $user->role === 'student') {
        return redirect()->route('student.dashboard');
    }

    if ($user && $user->role === 'dosen') {
        return redirect()->route('lecturer.dashboard');
    }

    return app(\App\Http\Controllers\DashboardController::class)->index();
})->name('dashboard')->middleware('auth');

Route::get('/students/export', [ExportController::class, 'export'])->name('students.export')->middleware(['auth', 'role:admin']);

Route::middleware(['auth', 'role:admin,dosen'])->group(function () {
    Route::get('/students', [StudentController::class, 'index'])->name('students.index');
    Route::get('/students/{student}', [StudentController::class, 'show'])->name('students.show');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/students/create', [StudentController::class, 'create'])->name('students.create');
    Route::post('/students', [StudentController::class, 'store'])->name('students.store');
    Route::get('/students/{student}/edit', [StudentController::class, 'edit'])->name('students.edit');
    Route::put('/students/{student}', [StudentController::class, 'update'])->name('students.update');
    Route::delete('/students/{student}', [StudentController::class, 'destroy'])->name('students.destroy');
});

// Admin management routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::resource('lecturers', \App\Http\Controllers\LecturerController::class);
    Route::resource('courses', \App\Http\Controllers\CourseController::class);
});

// Student portal routes (Mahasiswa)
Route::prefix('student')->name('student.')->middleware('auth')->group(function () {
    Route::get('/', [\App\Http\Controllers\StudentPortalController::class, 'dashboard'])->name('dashboard');
    Route::get('/assignments', [\App\Http\Controllers\StudentPortalController::class, 'assignmentsIndex'])->name('assignments.index');
    Route::get('/assignments/{id}', [\App\Http\Controllers\StudentPortalController::class, 'assignmentsShow'])->name('assignments.show');
    Route::get('/assignments/{id}/download-question', [\App\Http\Controllers\StudentPortalController::class, 'downloadQuestion'])->name('assignments.downloadQuestion');
    Route::post('/assignments/{id}/submit', [\App\Http\Controllers\StudentPortalController::class, 'submitAssignment'])->name('assignments.submit');

    Route::get('/attendance', [\App\Http\Controllers\StudentPortalController::class, 'attendanceIndex'])->name('attendance.index');
    Route::post('/attendance/{id}/mark', [\App\Http\Controllers\StudentPortalController::class, 'attendanceMark'])->name('attendance.mark');

    Route::get('/profile/edit', [\App\Http\Controllers\StudentPortalController::class, 'editProfile'])->name('profile.edit');
    Route::post('/profile', [\App\Http\Controllers\StudentPortalController::class, 'updateProfile'])->name('profile.update');
});

// Lecturer portal routes (Dosen)
Route::prefix('lecturer')->name('lecturer.')->middleware(['auth', 'role:dosen'])->group(function () {
    Route::get('/', [\App\Http\Controllers\LecturerPortalController::class, 'dashboard'])->name('dashboard');

    Route::get('/courses', [\App\Http\Controllers\LecturerPortalController::class, 'coursesIndex'])->name('courses.index');

    Route::get('/assignments', [\App\Http\Controllers\LecturerPortalController::class, 'assignmentsIndex'])->name('assignments.index');
    Route::get('/assignments/create', [\App\Http\Controllers\LecturerPortalController::class, 'assignmentsCreate'])->name('assignments.create');
    Route::post('/assignments', [\App\Http\Controllers\LecturerPortalController::class, 'assignmentsStore'])->name('assignments.store');
    Route::get('/assignments/{id}/submissions', [\App\Http\Controllers\LecturerPortalController::class, 'submissionsIndex'])->name('assignments.submissions');
    Route::post('/submissions/{id}/approve', [\App\Http\Controllers\LecturerPortalController::class, 'submissionApprove'])->name('submissions.approve');
    Route::post('/submissions/{id}/reject', [\App\Http\Controllers\LecturerPortalController::class, 'submissionReject'])->name('submissions.reject');

    Route::get('/attendance', [\App\Http\Controllers\LecturerPortalController::class, 'attendanceIndex'])->name('attendance.index');
    Route::get('/attendance/{id}/records', [\App\Http\Controllers\LecturerPortalController::class, 'attendanceRecords'])->name('attendance.records');
    Route::post('/attendance-records/{id}/approve', [\App\Http\Controllers\LecturerPortalController::class, 'attendanceApprove'])->name('attendance.approve');
    Route::post('/attendance-records/{id}/reject', [\App\Http\Controllers\LecturerPortalController::class, 'attendanceReject'])->name('attendance.reject');
});
