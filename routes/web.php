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
use App\Livewire\Classrooms\AssignStudents as ClassroomsAssignStudents;
use App\Livewire\Students\Index as StudentsIndex;
use App\Livewire\Students\Create as StudentsCreate;
use App\Livewire\Students\Edit as StudentsEdit;
use App\Livewire\Students\Show as StudentsShow;
use App\Livewire\Attendance\Overview as AttendanceOverview;
use App\Livewire\Schedules\Index as SchedulesIndex;
use App\Livewire\Schedules\Create as SchedulesCreate;
use App\Livewire\Schedules\Edit as SchedulesEdit;
use App\Livewire\Schedules\Show as SchedulesShow;

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
    Route::get('/admin/classrooms/{classroom}', \App\Livewire\Classrooms\Show::class)->name('classrooms.show');
    Route::get('/admin/classrooms/{classroom}/edit', ClassroomsEdit::class)->name('classrooms.edit');
    Route::get('/admin/classrooms/{classroom}/assign-students', ClassroomsAssignStudents::class)->name('classrooms.assign-students');
    Route::get('/admin/classrooms/{classroom}/attendance', \App\Livewire\Attendance\TakeAttendance::class)->name('classrooms.attendance');
    Route::get('/admin/classrooms/{classroom}/attendance-history', \App\Livewire\Attendance\AttendanceHistory::class)->name('classrooms.attendance-history');
    Route::get('/admin/students', StudentsIndex::class)->name('students.index');
    Route::get('/admin/students/create', StudentsCreate::class)->name('students.create');
    Route::get('/admin/students/{student}/edit', StudentsEdit::class)->name('students.edit');
    Route::get('/admin/students/{student}', StudentsShow::class)->name('students.show');
    Route::get('/admin/attendances', AttendanceOverview::class)->name('attendances.overview');

    // Schedules routes
    Route::get('/admin/schedules', SchedulesIndex::class)->name('schedules.index');
    Route::get('/admin/schedules/create', SchedulesCreate::class)->name('schedules.create');
    Route::get('/admin/schedules/{classroom}/edit', SchedulesEdit::class)->name('schedules.edit');
    Route::get('/admin/schedules/{classroom}', SchedulesShow::class)->name('schedules.show');
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
    Route::get('/admin/assignments/create', \App\Livewire\Assignments\Create::class)->name('assignments.create');
    Route::get('/admin/assignments', \App\Livewire\Assignments\Overview::class)->name('assignments.overview');
});
