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
