<?php

use App\Livewire\Admin\Home;
use Illuminate\Http\Request;
use App\Livewire\Auth\Login;
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

Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['vi', 'en', 'zh'])) {
        session(['locale' => $locale]);
    }
    return redirect()->back();
})->name('lang.switch');

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
    Route::get('/admin/chat/download/{messageId}', [\App\Livewire\Admin\Chat\Index::class, 'downloadAttachment'])->name('chat.download');

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
    Route::get('/admin/reports/schedule-conflicts', \App\Livewire\Admin\Reports\ScheduleConflictReport::class)->name('reports.schedule-conflicts');

    // Finance statistics
    Route::get('/admin/finance', \App\Livewire\Admin\Finance\Index::class)->name('admin.finance.index');
    Route::get('/admin/finance/payment/{user}', \App\Livewire\Admin\Finance\ShowPayment::class)->name('admin.finance.payment.show');
    Route::get('/admin/finance/expenses', \App\Livewire\Admin\Finance\ExpenseManagement::class)->name('admin.finance.expenses');

    // Evaluation Management routes
    Route::get('/admin/evaluation-management', \App\Livewire\Admin\EvaluationManagement\Index::class)->name('evaluation-management');

    // AI routes
    Route::get('/admin/ai', \App\Livewire\Admin\AI\Index::class)->name('ai.index');
    Route::get('/admin/ai/grading/{submissionId}', \App\Livewire\Admin\AI\AIGrading::class)->name('ai.grading');
    Route::get('/admin/ai/quiz-generator', \App\Livewire\Admin\AI\AIQuizGenerator::class)->name('ai.quiz-generator');
    Route::get('/admin/ai/question-bank-generator', \App\Livewire\Admin\AI\QuestionBankGenerator::class)->name('ai.question-bank-generator');
});

// Teacher routes
Route::middleware(['auth', 'role:teacher'])->name('teacher.')->group(function () {
    // My Class routes
    Route::get('/teacher/my-class', \App\Livewire\Teacher\MyClass\Index::class)->name('my-class.index');
    Route::get('/teacher/my-class/{classroomId}', \App\Livewire\Teacher\MyClass\Show::class)->name('my-class.show');

    // Other teacher routes...
    Route::get('/teacher/quizzes', \App\Livewire\Teacher\Quizzes\Index::class)->name('quizzes.index');
    Route::get('/teacher/quizzes/create', \App\Livewire\Teacher\Quizzes\Create::class)->name('quizzes.create');
    Route::get('/teacher/quizzes/{quiz}', \App\Livewire\Teacher\Quizzes\Show::class)->name('quizzes.show');
    Route::get('/teacher/quizzes/{quiz}/edit', \App\Livewire\Teacher\Quizzes\Edit::class)->name('quizzes.edit');
    Route::get('/teacher/quizzes/{quiz}/results', \App\Livewire\Teacher\Quizzes\Results::class)->name('quizzes.results');
    // Assignments routes
    Route::get('/teacher/assignments', \App\Livewire\Teacher\Assignments\Index::class)->name('assignments.index');
    Route::get('/teacher/assignments/create', \App\Livewire\Teacher\Assignments\Create::class)->name('assignments.create');
    Route::get('/teacher/assignments/{assignment}/edit', \App\Livewire\Teacher\Assignments\Edit::class)->name('assignments.edit');
    Route::get('/teacher/assignments/{assignment}', \App\Livewire\Teacher\Assignments\Show::class)->name('assignments.show');

    // Grading routes
    Route::get('/teacher/grading', \App\Livewire\Teacher\Grading\GradingList::class)->name('grading.index');
    Route::get('/teacher/grading/{assignment}', \App\Livewire\Teacher\Grading\GradeAssignment::class)->name('grading.grade-assignment');

    // Lessons routes
    Route::get('/teacher/lessons', \App\Livewire\Teacher\Lessons\Index::class)->name('lessons.index');
    Route::get('/teacher/lessons/create', \App\Livewire\Teacher\Lessons\Create::class)->name('lessons.create');
    Route::get('/teacher/lessons/{lesson}', \App\Livewire\Teacher\Lessons\Show::class)->name('lessons.show');
    Route::get('/teacher/lessons/{lesson}/edit', \App\Livewire\Teacher\Lessons\Edit::class)->name('lessons.edit');

    // Notifications routes
    Route::get('/teacher/notifications', \App\Livewire\Teacher\Notifications\Index::class)->name('notifications.index');

    // Attendance routes
    Route::get('/teacher/attendance', \App\Livewire\Teacher\Attendance\Overview::class)->name('attendance.overview');
    Route::get('/teacher/attendance/history', \App\Livewire\Teacher\Attendance\History::class)->name('attendance.history');
    Route::get('/teacher/attendance/{classroom}/take', \App\Livewire\Teacher\Attendance\TakeAttendance::class)->name('attendance.take');
    Route::get('/teacher/attendance/{classroom}/attendance-history', \App\Livewire\Teacher\Attendance\AttendanceHistory::class)->name('attendance.classroom-history');

    // Schedules routes
    Route::get('/teacher/schedules', \App\Livewire\Teacher\Schedules\Index::class)->name('schedules.index');

    // Chat routes
    Route::get('/teacher/chat', \App\Livewire\Teacher\Chat\Index::class)->name('chat.index');
    Route::get('/teacher/chat/download/{messageId}', [\App\Livewire\Teacher\Chat\Index::class, 'downloadAttachment'])->name('chat.download');
    Route::get('/teacher/chat/test', \App\Livewire\Teacher\Chat\Test::class)->name('chat.test');

    // Báo cáo - Reports cho giáo viên
    Route::get('/teacher/reports', \App\Livewire\Teacher\Reports\Index::class)->name('reports.index');

    // Báo cáo đánh giá sinh viên
    Route::get('/teacher/evaluations', \App\Livewire\Teacher\EvaluationReport::class)->name('evaluations.report');

    // AI routes
    Route::get('/teacher/ai', \App\Livewire\Teacher\AI\Index::class)->name('ai.index');
    Route::get('/teacher/ai/grading/{submissionId}', \App\Livewire\Teacher\AI\AIGrading::class)->name('ai.grading');
    Route::get('/teacher/ai/quiz-generator', \App\Livewire\Teacher\AI\AIQuizGenerator::class)->name('ai.quiz-generator');
    Route::get('/teacher/ai/question-bank-generator', \App\Livewire\Teacher\AI\QuestionBankGenerator::class)->name('ai.question-bank-generator');
});

// Student routes
Route::middleware(['auth', 'verified', 'role:student'])->name('student.')->prefix('student')->group(function () {
    // Other student routes...
    Route::get('/lessons', \App\Livewire\Student\Lessons\Index::class)->name('lessons.index');
    Route::get('/lessons/{lessonId}', \App\Livewire\Student\Lessons\Show::class)->name('lessons.show');
    Route::get('/assignments', \App\Livewire\Student\Assignments\Index::class)->name('assignments.overview');
    Route::get('/assignments/submissions', \App\Livewire\Student\Assignments\MySubmissions::class)->name('assignments.submissions');
    Route::get('/assignments/{assignmentId}', \App\Livewire\Student\Assignments\Show::class)->name('assignments.show');
    Route::get('/assignments/{assignmentId}/submit', \App\Livewire\Student\Assignments\Submit::class)->name('assignments.submit');
    Route::get('/quizzes/{quiz}/do', \App\Livewire\Student\Quiz\DoQuiz::class)->name('quizzes.do');
    Route::get('/quizzes/{quizId}/review', \App\Livewire\Student\Quiz\Review::class)->name('quizzes.review');
    Route::get('/quizzes', \App\Livewire\Student\Quiz\Index::class)->name('quizzes.index');

    // Notifications routes
    Route::get('/notifications', StudentNotificationsIndex::class)->name('notifications.index');
    // Kết quả học tập
    Route::get('/reports', \App\Livewire\Student\Reports\Index::class)->name('reports.index');
    // Lịch học
    Route::get('/schedules', \App\Livewire\Student\Schedules\Index::class)->name('schedules');
    Route::get('/chat', App\Livewire\Student\Chat\Index::class)->name('chat.index');
    Route::get('/chat/download/{messageId}', [App\Livewire\Student\Chat\Index::class, 'downloadAttachment'])->name('chat.download');

    // Đánh giá chất lượng học
    Route::get('/evaluation', \App\Livewire\Student\Evaluation\Index::class)->name('evaluation');
});
