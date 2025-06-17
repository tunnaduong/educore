<?php

use App\Livewire\Home;
use App\Livewire\Auth\Login;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Livewire\Users\Edit as UsersEdit;
use App\Livewire\Users\Index as UsersIndex;
use App\Livewire\Users\Create as UsersCreate;
use Illuminate\Http\Request;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

Route::get('/dashboard/home', Home::class)->name('dashboard')->middleware(['auth']);
Route::get('/login', Login::class)->name('login');
Route::get('/dashboard/users', UsersIndex::class)->name('users.index');
Route::get('/dashboard/users/{user}/edit', UsersEdit::class)->name('users.edit');
Route::get('/dashboard/users/create', UsersCreate::class)->name('users.create');
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('login');
})->name('logout');
