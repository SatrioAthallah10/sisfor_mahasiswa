<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\LocaleController;
use Illuminate\Support\Facades\Route;

// Redirect Root to Login (or Dashboard if logged in)
Route::redirect('/', '/login');

// Locale Switcher Route
Route::get('/lang/{locale}', [LocaleController::class, 'switch'])->name('lang.switch');

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Dashboard Route
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');

// CSV Export Route - must reside before resource routing to prevent conflict with students.show
Route::get('/students/export', [ExportController::class, 'export'])->name('students.export')->middleware('auth');

// Resourceful CRUD Routes for Students
Route::resource('students', StudentController::class)->middleware('auth');
