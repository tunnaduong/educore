<?php

use App\Livewire\Home;
use App\Livewire\Auth\Login;
use Illuminate\Support\Facades\Route;
use App\Livewire\Users\Edit as UsersEdit;
use App\Livewire\Users\Index as UsersIndex;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', Home::class)->name('dashboard')->middleware(['auth']);
Route::get('/login', Login::class)->name('login');
Route::get('/users', UsersIndex::class)->name('users.index');
Route::get('/users/{user}/edit', UsersEdit::class)->name('users.edit');
