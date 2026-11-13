<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BidController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('projects.index'));

// --- Autentikasi
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// --- Proyek (baca: publik)
Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');

// --- Proyek (tulis: klien). Harus sebelum /projects/{project} agar "create" tidak dianggap id.
Route::middleware(['auth', 'role:klien'])->group(function () {
    Route::get('/projects/create', [ProjectController::class, 'create'])->name('projects.create');
    Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
    Route::get('/projects/{project}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
    Route::put('/projects/{project}', [ProjectController::class, 'update'])->name('projects.update');
    Route::delete('/projects/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');
});

Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('projects.show');

// --- Dasbor (klien & developer)
Route::get('/dashboard', DashboardController::class)
    ->middleware(['auth', 'role:klien,developer'])
    ->name('dashboard');

// --- Admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('index');
    Route::patch('/users/{user}/role', [AdminController::class, 'updateRole'])->name('users.role');
    Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');
    Route::delete('/projects/{project}', [AdminController::class, 'destroyProject'])->name('projects.destroy');
});

// --- Lelang
Route::post('/projects/{project}/bids', [BidController::class, 'store'])
    ->middleware(['auth', 'role:developer'])
    ->name('projects.bids.store');

Route::post('/projects/{project}/bids/{bid}/pick', [BidController::class, 'pick'])
    ->middleware(['auth', 'role:klien'])
    ->scopeBindings() // {bid} harus milik {project}, jika tidak 404
    ->name('projects.bids.pick');
