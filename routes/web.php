<?php

use App\Http\Controllers\Admin\CourseController as AdminCourseController;
use App\Http\Controllers\Admin\LecturerController as AdminLecturerController;
use App\Http\Controllers\Admin\ScheduleController as AdminScheduleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\ScheduleController;
use Illuminate\Support\Facades\Route;

// Public / Home
Route::get('/', [DashboardController::class, 'index'])->name('home');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/jadwal-hari-ini', [ScheduleController::class, 'today'])->name('jadwal.hari-ini');
Route::get('/semua-jadwal', [ScheduleController::class, 'index'])->name('jadwal.semua');

// Academic Info Pages
Route::get('/mata-kuliah', [CourseController::class, 'index'])->name('mata-kuliah.index');
Route::get('/daftar-pengajar', [\App\Http\Controllers\LecturerController::class, 'index'])->name('pengajar.index');
Route::get('/ruang-kuliah', [RoomController::class, 'index'])->name('ruang.index');
Route::middleware('auth')->group(function () {
    Route::get('/pengaturan-akun', [AccountController::class, 'edit'])->name('pengaturan.akun');
    Route::put('/pengaturan-akun/password', [AccountController::class, 'updatePassword'])->name('pengaturan.password');
});

// Authentication Routes (Multi-Role: Mahasiswa, Dosen & Admin)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::get('/login/mahasiswa', [AuthController::class, 'showLoginForm'])->name('login.mahasiswa');
Route::get('/login/dosen', [AuthController::class, 'showLoginForm'])->name('login.dosen');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/lupa-password', [PasswordResetController::class, 'requestForm'])->name('password.request');
Route::post('/lupa-password', [PasswordResetController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [PasswordResetController::class, 'resetForm'])->name('password.reset');
Route::post('/reset-password', [PasswordResetController::class, 'reset'])->name('password.update');

// Admin Management (CRUD) - Restricted to Admin role only
Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
    // Schedule CRUD
    Route::get('/jadwal', [AdminScheduleController::class, 'index'])->name('jadwal.index');
    Route::post('/jadwal', [AdminScheduleController::class, 'store'])->name('jadwal.store');
    Route::put('/jadwal/{id}', [AdminScheduleController::class, 'update'])->name('jadwal.update');
    Route::delete('/jadwal/{id}', [AdminScheduleController::class, 'destroy'])->name('jadwal.destroy');

    // Course CRUD
    Route::get('/mata-kuliah', [AdminCourseController::class, 'index'])->name('courses.index');
    Route::post('/mata-kuliah', [AdminCourseController::class, 'store'])->name('courses.store');
    Route::put('/mata-kuliah/{id}', [AdminCourseController::class, 'update'])->name('courses.update');
    Route::delete('/mata-kuliah/{id}', [AdminCourseController::class, 'destroy'])->name('courses.destroy');

    // Lecturer CRUD
    Route::get('/pengajar', [AdminLecturerController::class, 'index'])->name('lecturers.index');
    Route::post('/pengajar', [AdminLecturerController::class, 'store'])->name('lecturers.store');
    Route::put('/pengajar/{id}', [AdminLecturerController::class, 'update'])->name('lecturers.update');
    Route::delete('/pengajar/{id}', [AdminLecturerController::class, 'destroy'])->name('lecturers.destroy');
    Route::get('/akun', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
    Route::post('/akun', [\App\Http\Controllers\Admin\UserController::class, 'store'])->name('users.store');
    Route::put('/akun/{id}', [\App\Http\Controllers\Admin\UserController::class, 'update'])->name('users.update');
    Route::delete('/akun/{id}', [\App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');
});
