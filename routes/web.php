<?php

use App\Http\Controllers\Admin\CourseController as AdminCourseController;
use App\Http\Controllers\Admin\LecturerController as AdminLecturerController;
use App\Http\Controllers\Admin\RoomController as AdminRoomController;
use App\Http\Controllers\Admin\ScheduleGeneratorController;
use App\Http\Controllers\Admin\ScheduleController as AdminScheduleController;
use App\Http\Controllers\Admin\AcademicYearController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\ScheduleController;
use Illuminate\Support\Facades\Route;

// Halaman akademik dan jadwal hanya dapat dilihat setelah login.
Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('home');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/jadwal-hari-ini', [ScheduleController::class, 'today'])->name('jadwal.hari-ini');
    Route::get('/semua-jadwal', [ScheduleController::class, 'index'])->name('jadwal.semua');
    Route::get('/semua-jadwal/cetak', [ScheduleController::class, 'print'])->name('jadwal.cetak');

    Route::get('/mata-kuliah', [CourseController::class, 'index'])->name('mata-kuliah.index');
    Route::get('/mata-kuliah/export', [CourseController::class, 'export'])->name('mata-kuliah.export');
    Route::get('/mata-kuliah/export/pdf', [CourseController::class, 'exportPdf'])->name('mata-kuliah.export.pdf');
    Route::get('/daftar-pengajar', [\App\Http\Controllers\LecturerController::class, 'index'])->name('pengajar.index');
    Route::get('/ruang-kuliah', [RoomController::class, 'index'])->name('ruang.index');
});
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
    /* Generator Jadwal dinonaktifkan sementara, kode tetap disimpan.
    Route::get('/generator-jadwal', [ScheduleGeneratorController::class, 'index'])->name('schedule-generator.index');
    Route::post('/generator-jadwal', [ScheduleGeneratorController::class, 'generate'])->name('schedule-generator.generate');
    Route::get('/generator-jadwal/{generation}/cetak', [ScheduleGeneratorController::class, 'print'])->name('schedule-generator.print');
    Route::delete('/generator-jadwal/{generation}', [ScheduleGeneratorController::class, 'destroy'])->name('schedule-generator.destroy');
    */

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
    Route::get('/ruang', [AdminRoomController::class, 'index'])->name('rooms.index');
    Route::post('/ruang', [AdminRoomController::class, 'store'])->name('rooms.store');
    Route::put('/ruang/{room}', [AdminRoomController::class, 'update'])->name('rooms.update');
    Route::delete('/ruang/{room}', [AdminRoomController::class, 'destroy'])->name('rooms.destroy');
    Route::get('/akun', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
    Route::post('/akun', [\App\Http\Controllers\Admin\UserController::class, 'store'])->name('users.store');
    Route::put('/akun/{id}', [\App\Http\Controllers\Admin\UserController::class, 'update'])->name('users.update');
    Route::delete('/akun/{id}', [\App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');
    Route::get('/periode-akademik', [AcademicYearController::class, 'index'])->name('academic-years.index');
    Route::post('/periode-akademik', [AcademicYearController::class, 'store'])->name('academic-years.store');
    Route::put('/periode-akademik/{academicYear}', [AcademicYearController::class, 'update'])->name('academic-years.update');
    Route::post('/periode-akademik/{academicYear}/aktifkan', [AcademicYearController::class, 'activate'])->name('academic-years.activate');
    Route::delete('/periode-akademik/{academicYear}', [AcademicYearController::class, 'destroy'])->name('academic-years.destroy');
});
