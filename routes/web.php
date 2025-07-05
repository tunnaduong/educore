<?php

use App\Livewire\Admin\Home;
use Illuminate\Http\Request;
use App\Livewire\Admin\Auth\Login;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\Users\Edit as UsersEdit;
use App\Livewire\Admin\Users\Index as UsersIndex;
use App\Livewire\Admin\Users\Create as UsersCreate;
use App\Livewire\Admin\Students\Edit as StudentsEdit;
use App\Livewire\Admin\Students\Show as StudentsShow;
use App\Livewire\Admin\Schedules\Edit as SchedulesEdit;
use App\Livewire\Admin\Schedules\Show as SchedulesShow;
use App\Livewire\Admin\Students\Index as StudentsIndex;
use App\Livewire\Admin\Classrooms\Edit as ClassroomsEdit;
use App\Livewire\Admin\Schedules\Index as SchedulesIndex;
use App\Livewire\Admin\Students\Create as StudentsCreate;
use App\Livewire\Admin\Classrooms\Index as ClassroomsIndex;
use App\Livewire\Admin\Schedules\Create as SchedulesCreate;
use App\Livewire\Admin\Classrooms\Create as ClassroomsCreate;
use App\Livewire\Admin\Attendance\Overview as AttendanceOverview;
use App\Livewire\Admin\Classrooms\AssignStudents as ClassroomsAssignStudents;

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
    Route::get('/users', UsersIndex::class)->name('users.index');
    Route::get('/users/{user}/edit', UsersEdit::class)->name('users.edit');
    Route::get('/users/create', UsersCreate::class)->name('users.create');
    Route::get('/classrooms', ClassroomsIndex::class)->name('classrooms.index');
    Route::get('/classrooms/create', ClassroomsCreate::class)->name('classrooms.create');
    Route::get('/classrooms/{classroom}', \App\Livewire\Admin\Classrooms\Show::class)->name('classrooms.show');
    Route::get('/classrooms/{classroom}/edit', ClassroomsEdit::class)->name('classrooms.edit');
    Route::get('/classrooms/{classroom}/assign-students', ClassroomsAssignStudents::class)->name('classrooms.assign-students');
    Route::get('/classrooms/{classroom}/attendance', \App\Livewire\Admin\Attendance\TakeAttendance::class)->name('classrooms.attendance');
    Route::get('/classrooms/{classroom}/attendance-history', \App\Livewire\Admin\Attendance\AttendanceHistory::class)->name('classrooms.attendance-history');
    Route::get('/students', StudentsIndex::class)->name('students.index');
    Route::get('/students/create', StudentsCreate::class)->name('students.create');
    Route::get('/students/{student}/edit', StudentsEdit::class)->name('students.edit');
    Route::get('/students/{student}', StudentsShow::class)->name('students.show');
    Route::get('/attendances', AttendanceOverview::class)->name('attendances.overview');
    Route::get('/attendances/history', \App\Livewire\Admin\Attendance\History::class)->name('attendances.history');

    // Schedules routes
    Route::get('/schedules', SchedulesIndex::class)->name('schedules.index');
    Route::get('/schedules/create', SchedulesCreate::class)->name('schedules.create');
    Route::get('/schedules/{classroom}/edit', SchedulesEdit::class)->name('schedules.edit');
    Route::get('/schedules/{classroom}', SchedulesShow::class)->name('schedules.show');
});

// Teacher routes
Route::middleware(['auth', 'role:teacher'])->group(function () {
    // Other teacher routes...
});

// Student routes
Route::middleware(['auth', 'role:student'])->group(function () {
    // Other student routes...
    Route::get('/dashboard', Home::class)->name('dashboard');
});

// Shared routes for admin and teacher
Route::middleware(['auth', 'role:admin,teacher'])->group(function () {
    // Other shared routes...

<<<<<<< Updated upstream
    Route::get('/assignments/create', \App\Livewire\Admin\Assignments\Create::class)->name('assignments.create');
    Route::get('/assignments', \App\Livewire\Admin\Assignments\Overview::class)->name('assignments.overview');
    Route::get('/assignments/{assignmentId}', \App\Livewire\Admin\Assignments\Show::class)->name('assignments.show');
    Route::get('/assignments/{assignmentId}/edit', \App\Livewire\Admin\Assignments\Edit::class)->name('assignments.edit');
});

Route::middleware(['auth', 'role:admin,teacher,student'])->group(function () {
    Route::get('/dashboard', Home::class)->name('dashboard');
    
    // Quiz routes
    Route::get('/quizzes', \App\Livewire\Admin\Quiz\Index::class)->name('quizzes.index');
    Route::get('/quizzes/create', \App\Livewire\Admin\Quiz\Create::class)->name('quizzes.create');
    Route::get('/quizzes/{quiz}', \App\Livewire\Admin\Quiz\Show::class)->name('quizzes.show');
    Route::get('/quizzes/{quiz}/edit', \App\Livewire\Admin\Quiz\Edit::class)->name('quizzes.edit');
    Route::get('/quizzes/{quiz}/do', \App\Livewire\Admin\Quiz\DoQuiz::class)->name('quizzes.do');
    Route::get('/quizzes/{quiz}/results', \App\Livewire\Admin\Quiz\Results::class)->name('quizzes.results');
=======
    Route::get('/assignments/create', \App\Livewire\Assignments\Create::class)->name('assignments.create');
    Route::get('/assignments', \App\Livewire\Assignments\Overview::class)->name('assignments.overview');

    Route::get('/admin/assignments/create', \App\Livewire\Assignments\Create::class)->name('assignments.create');
    Route::get('/admin/assignments', \App\Livewire\Assignments\Overview::class)->name('assignments.overview');
    Route::get('/admin/assignments/{assignmentId}', \App\Livewire\Assignments\Show::class)->name('assignments.show');
    Route::get('/admin/assignments/{assignmentId}/edit', \App\Livewire\Assignments\Edit::class)->name('assignments.edit');
>>>>>>> Stashed changes
});
