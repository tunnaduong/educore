<?php

use App\Livewire\Home;
use App\Livewire\Auth\Login;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Livewire\Users\Edit as UsersEdit;
use App\Livewire\Users\Index as UsersIndex;
use App\Livewire\Users\Create as UsersCreate;
use Illuminate\Http\Request;
use App\Livewire\Classrooms\Index as ClassroomsIndex;
use App\Livewire\Classrooms\Create as ClassroomsCreate;
use App\Livewire\Classrooms\Edit as ClassroomsEdit;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

Route::get('/login', Login::class)->name('login');
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('login');
})->name('logout');

// Admin routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/home', Home::class)->name('dashboard');
    Route::get('/admin/users', UsersIndex::class)->name('users.index');
    Route::get('/admin/users/{user}/edit', UsersEdit::class)->name('users.edit');
    Route::get('/admin/users/create', UsersCreate::class)->name('users.create');
    Route::get('/admin/classrooms', ClassroomsIndex::class)->name('classrooms.index');
    Route::get('/admin/classrooms/create', ClassroomsCreate::class)->name('classrooms.create');
    Route::get('/admin/classrooms/{classroom}/edit', ClassroomsEdit::class)->name('classrooms.edit');
});

// Teacher routes
Route::middleware(['auth', 'role:teacher'])->group(function () {
    // Other teacher routes...
});

// Student routes
Route::middleware(['auth', 'role:student'])->group(function () {
    // Other student routes...
});

// Shared routes for admin and teacher
Route::middleware(['auth', 'role:admin,teacher'])->group(function () {
    // Other shared routes...
});
