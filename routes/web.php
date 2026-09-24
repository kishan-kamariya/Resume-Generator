<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ResumeController;
use App\Http\Controllers\ChatbotController;
use Illuminate\Support\Facades\Route;

// Landing page redirect
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// Auth routes (guests only)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Resume CRUD
    Route::get('/resume/create', [ResumeController::class, 'create'])->name('resume.create');
    Route::post('/resume', [ResumeController::class, 'store'])->name('resume.store');
    Route::get('/resume/{resume}/edit', [ResumeController::class, 'edit'])->name('resume.edit');
    Route::put('/resume/{resume}', [ResumeController::class, 'update'])->name('resume.update');
    Route::get('/resume/{resume}/preview', [ResumeController::class, 'preview'])->name('resume.preview');
    Route::get('/resume/{resume}/download', [ResumeController::class, 'download'])->name('resume.download');
    Route::delete('/resume/{resume}', [ResumeController::class, 'destroy'])->name('resume.destroy');
    Route::post('/resume/{resume}/duplicate', [ResumeController::class, 'duplicate'])->name('resume.duplicate');

    // AI Chatbot
    Route::post('/chatbot', [ChatbotController::class, 'chat'])->name('chatbot.chat');
});
