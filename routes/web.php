<?php

use App\Livewire\Admin\Home;
use Illuminate\Http\Request;
use App\Livewire\Admin\Auth\Login;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\Grading\GradingList;
use App\Livewire\Admin\Grading\GradeAssignment;
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
use App\Livewire\Admin\Notifications\Index as AdminNotificationsIndex;
use App\Livewire\Student\Notifications\Index as StudentNotificationsIndex;

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

Route::middleware(['auth', 'role:admin,teacher,student'])->group(function () {
    Route::get('/dashboard', Home::class)->name('dashboard');
});

// Admin routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/users', UsersIndex::class)->name('users.index');
    Route::get('/admin/users/{user}/edit', UsersEdit::class)->name('users.edit');
    Route::get('/admin/users/create', UsersCreate::class)->name('users.create');
    Route::get('/admin/classrooms', ClassroomsIndex::class)->name('classrooms.index');
    Route::get('/admin/classrooms/create', ClassroomsCreate::class)->name('classrooms.create');
    Route::get('/admin/classrooms/{classroom}', \App\Livewire\Admin\Classrooms\Show::class)->name('classrooms.show');
    Route::get('/admin/classrooms/{classroom}/edit', ClassroomsEdit::class)->name('classrooms.edit');
    Route::get('/admin/classrooms/{classroom}/assign-students', ClassroomsAssignStudents::class)->name('classrooms.assign-students');
    Route::get('/admin/classrooms/{classroom}/attendance', \App\Livewire\Admin\Attendance\TakeAttendance::class)->name('classrooms.attendance');
    Route::get('/admin/classrooms/{classroom}/attendance-history', \App\Livewire\Admin\Attendance\AttendanceHistory::class)->name('classrooms.attendance-history');
    Route::get('/admin/students', StudentsIndex::class)->name('students.index');
    Route::get('/admin/students/create', StudentsCreate::class)->name('students.create');
    Route::get('/admin/students/{student}/edit', StudentsEdit::class)->name('students.edit');
    Route::get('/admin/students/{student}', StudentsShow::class)->name('students.show');
    Route::get('/admin/attendances', AttendanceOverview::class)->name('attendances.overview');
    Route::get('/admin/attendances/history', \App\Livewire\Admin\Attendance\History::class)->name('attendances.history');

    // Quiz routes
    Route::get('/admin/quizzes', \App\Livewire\Admin\Quiz\Index::class)->name('quizzes.index');
    Route::get('/admin/quizzes/create', \App\Livewire\Admin\Quiz\Create::class)->name('quizzes.create');
    Route::get('/admin/quizzes/{quiz}', \App\Livewire\Admin\Quiz\Show::class)->name('quizzes.show');
    Route::get('/admin/quizzes/{quiz}/edit', \App\Livewire\Admin\Quiz\Edit::class)->name('quizzes.edit');
    Route::get('/admin/quizzes/{quiz}/results', \App\Livewire\Admin\Quiz\Results::class)->name('quizzes.results');

    // Schedules routes
    Route::get('/admin/schedules', SchedulesIndex::class)->name('schedules.index');
    Route::get('/admin/schedules/create', SchedulesCreate::class)->name('schedules.create');
    Route::get('/admin/schedules/{classroom}/edit', SchedulesEdit::class)->name('schedules.edit');
    Route::get('/admin/schedules/{classroom}', SchedulesShow::class)->name('schedules.show');

    Route::get('/admin/assignments', \App\Livewire\Admin\Assignments\Overview::class)->name('assignments.overview');
    Route::get('/admin/assignments/list', \App\Livewire\Admin\Assignments\AssignmentList::class)->name('assignments.list');
    Route::get('/admin/assignments/create', \App\Livewire\Admin\Assignments\Create::class)->name('assignments.create');
    Route::get('/admin/assignments/{assignmentId}', \App\Livewire\Admin\Assignments\Show::class)->name('assignments.show');
    Route::get('/admin/assignments/{assignmentId}/edit', \App\Livewire\Admin\Assignments\Edit::class)->name('assignments.edit');
    Route::get('/admin/grading', GradingList::class)->name('grading.list');
    Route::get('/admin/grading/{assignment}', GradeAssignment::class)->name('grading.grade-assignment');
    // Chat routes
    Route::get('/admin/chat', \App\Livewire\Admin\Chat\Index::class)->name('chat.index');

    // Lesson routes
    Route::get('/admin/lessons', \App\Livewire\Admin\Lessons\Index::class)->name('lessons.index');
    Route::get('/admin/lessons/create', \App\Livewire\Admin\Lessons\Create::class)->name('lessons.create');
    Route::get('/admin/lessons/{lesson}/show', \App\Livewire\Admin\Lessons\Show::class)->name('lessons.show');
    Route::get('/admin/lessons/{lesson}/edit', \App\Livewire\Admin\Lessons\Edit::class)->name('lessons.edit');
    Route::delete('/admin/lessons/{lesson}', [\App\Livewire\Admin\Lessons\Show::class, 'deleteLesson'])->name('lessons.destroy');


    // Notifications routes
    Route::get('/admin/notifications', AdminNotificationsIndex::class)->name('notifications.index');

    // Reports routes
    Route::get('/admin/reports', \App\Livewire\Admin\Reports\Index::class)->name('reports.index');
    Route::get('/admin/reports/student/{student}', \App\Livewire\Admin\Reports\StudentReport::class)->name('reports.student');
    Route::get('/admin/reports/class/{classroom}', \App\Livewire\Admin\Reports\ClassReport::class)->name('reports.class');
});

// Teacher routes
Route::middleware(['auth', 'role:teacher'])->group(function () {
    // Other teacher routes...
});

// Shared routes for admin and teacher
Route::middleware(['auth', 'role:admin,teacher'])->group(function () {
    // Other shared routes...
});

// Student routes
Route::middleware(['auth', 'role:student'])->name('student.')->group(function () {
    // Other student routes...
    Route::get('/student/lessons', \App\Livewire\Student\Lessons\Index::class)->name('lessons.index');
    Route::get('/student/lessons/{lessonId}', \App\Livewire\Student\Lessons\Show::class)->name('lessons.show');
    Route::get('/student/assignments', \App\Livewire\Student\Assignments\Index::class)->name('assignments.overview');
    Route::get('/student/assignments/submissions', \App\Livewire\Student\Assignments\MySubmissions::class)->name('assignments.submissions');
    Route::get('/student/assignments/{assignmentId}', \App\Livewire\Student\Assignments\Show::class)->name('assignments.show');
    Route::get('/student/assignments/{assignmentId}/submit', \App\Livewire\Student\Assignments\Submit::class)->name('assignments.submit');
    Route::get('/student/quizzes/{quiz}/do', \App\Livewire\Student\Quiz\DoQuiz::class)->name('quizzes.do');
    Route::get('/student/quizzes/{quizId}/review', \App\Livewire\Student\Quiz\Review::class)->name('quizzes.review');
    Route::get('/student/quizzes', \App\Livewire\Student\Quiz\Index::class)->name('quizzes.index');

    // Notifications routes
    Route::get('/student/notifications', StudentNotificationsIndex::class)->name('notifications.index');
});
