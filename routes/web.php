<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// 🔹 Controllers
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\SchoolYearController;
use App\Http\Controllers\AdditionalInformationController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\RiasecController;
use App\Http\Controllers\LifeValuesController;
use App\Http\Controllers\CurriculumController;
use App\Http\Controllers\ArchivedStudentDataController;
use App\Http\Controllers\AdminArchivedStudentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RecoveryController;
use App\Http\Controllers\EmailVerificationController;

// 🔹 Middleware
use App\Http\Middleware\PreventBackHistory;

// ----------------------
// Public / Recovery Routes
// ----------------------

Route::prefix('recovery')->group(function () {
    // Recovery page (checks static_admin session)
    Route::get('/', [RecoveryController::class, 'index'])
        ->name('recovery.index');

    // Static admin login
    Route::post('/login', [RecoveryController::class, 'login'])
        ->name('recovery.login');

    // Backup upload
    Route::post('/upload', [RecoveryController::class, 'upload'])
        ->name('recovery.upload');

    // Logout static admin
    Route::post('/logout', [RecoveryController::class, 'logout'])
        ->name('recovery.logout');
});



// Landing page
Route::get('/', fn() => view('welcome'))->name('landing');

// ----------------------
// Guest-only routes
// ----------------------
Route::middleware(['guest', 'preventBackHistory'])->group(function () {
    // Email verification routes
    Route::get('email/verify', [EmailVerificationController::class, 'show'])->name('email.verify');
    Route::post('email/verify', [EmailVerificationController::class, 'verify']);
    Route::post('email/verification/resend', [EmailVerificationController::class, 'resend'])->name('email.verification.resend');
});

// ----------------------
// Authenticated routes
// ----------------------
Route::middleware(['auth', 'preventBackHistory'])->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::get('/dashboard', function () {
        $user = Auth::user();
        return match($user->role) {
            'superadmin' => redirect()->route('superadmin.dashboard'),
            'admin'      => redirect()->route('admin.dashboard'),
            'student'    => redirect()->route('student.dashboard'),
            default      => redirect()->route('landing'),
        };
    })->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Test Results (accessible to authenticated users)
    Route::get('/testing/results/riasec-result/{result_id?}', [RiasecController::class, 'result'])
        ->name('testing.results.riasec-result');
    Route::get('/testing/results/life-values-result/{result_id?}', [LifeValuesController::class, 'result'])
        ->name('testing.results.life-values-results');
});

// ----------------------
// Superadmin Routes
// ----------------------
Route::middleware(['auth', 'preventBackHistory', 'role:superadmin'])->prefix('superadmin')->group(function () {
    Route::get('/dashboard', [SuperAdminController::class, 'dashboard'])->name('superadmin.dashboard');

    // Accounts
    Route::get('/admin-accounts', [SuperAdminController::class, 'adminAccounts'])->name('superadmin.admin-accounts');
    Route::post('/admin-accounts/store', [SuperAdminController::class, 'storeAccount'])->name('superadmin.admin-accounts.store');
    Route::get('/admin-accounts/{id}/edit', [SuperAdminController::class, 'editAdminAccount'])->name('superadmin.admin-accounts.edit');
    Route::put('/admin-accounts/{id}/update', [SuperAdminController::class, 'updateAccount'])->name('superadmin.admin-accounts.update');

    Route::get('/student-accounts', [SuperAdminController::class, 'studentAccounts'])->name('superadmin.student-accounts');
    Route::patch('/users/{user}/toggleStatus', [SuperAdminController::class, 'toggleStatus'])->name('superadmin.users.toggleStatus');

    // Profile
    Route::get('/update-profile', [SuperAdminController::class, 'profile'])->name('superadmin.update-profile');
    Route::put('/update-profile/{user}', [SuperAdminController::class, 'updateProfile'])->name('superadmin.update-profile.update');

    // Backup & Restore
    Route::get('/backup-restore', [BackupController::class, 'index'])->name('superadmin.backup-restore');
    Route::get('/download', [BackupController::class, 'download'])->name('superadmin.download');
    Route::post('/upload', [BackupController::class, 'upload'])->withoutMiddleware(['verifyCsrfToken'])->name('superadmin.upload');

    // School Years
    Route::get('/school-year', [SchoolYearController::class, 'schoolYear'])->name('superadmin.school-year');
    Route::post('/school-year', [SchoolYearController::class, 'store'])->name('superadmin.school-year.store');
    Route::put('/school-year/{id}/archive', [SchoolYearController::class, 'archive'])->name('superadmin.school-year.archive');
    Route::put('/school-year/{id}/unarchive', [SchoolYearController::class, 'unarchive'])->name('superadmin.school-year.unarchive');

    // Curriculum
    Route::get('/curriculum', [CurriculumController::class, 'index'])->name('superadmin.curriculum');
    Route::get('/curriculum/create', [CurriculumController::class, 'create'])->name('superadmin.curriculum.create');
    Route::post('/curriculum/store', [CurriculumController::class, 'store'])->name('superadmin.curriculum.store');
    Route::put('/curriculum/update/{id}', [CurriculumController::class, 'update'])->name('superadmin.curriculum.update');
    Route::put('/curriculum/{id}/archive', [CurriculumController::class, 'archive'])->name('superadmin.curriculum.archive');
    Route::put('/curriculum/{id}/unarchive', [CurriculumController::class, 'unarchive'])->name('superadmin.curriculum.unarchive');

    // Archived Students
    Route::get('/archived-student-data', [ArchivedStudentDataController::class, 'studentData'])->name('superadmin.archived-student-data');
    Route::post('/archived-student-data/{schoolYear}/archive', [ArchivedStudentDataController::class, 'archive'])->name('superadmin.archive-students');
    Route::get('/archived-students/{id}', [ArchivedStudentDataController::class, 'getArchivedStudent'])->name('superadmin.archived.student.view');
    Route::get('/archived-files', [ArchivedStudentDataController::class, 'archivedFiles'])->name('superadmin.archived-files');
    Route::get('/archived-students/school-year/{schoolYearId}', [ArchivedStudentDataController::class, 'showArchivedStudents'])->name('superadmin.archived.student.ajax');

    // Student Profile
    Route::get('/student-profile', [SuperAdminController::class, 'studentProfile'])->name('superadmin.student-profile');
    Route::get('/students/{id}/additional-info', [SuperAdminController::class, 'getAdditionalInfo'])->name('superadmin.students.additional-info');
    Route::post('/update-student-info/{id}', [SuperAdminController::class, 'updateStudentInfo'])->name('superadmin.update-student-info');

    // Test Results
    Route::get('/test-results', [SuperAdminController::class, 'viewTestResults'])->name('superadmin.test-results');

    // Manage Test
    Route::get('/manage-test', [SuperAdminController::class, 'manageTest'])->name('superadmin.manage-test');
    Route::post('/manage-test/toggle', [SuperAdminController::class, 'toggleTest'])->name('superadmin.manage-test.toggle');
    Route::post('/reopen-riasec/{userId}', [RiasecController::class, 'reopenForStudent'])->name('superadmin.reopen-riasec');
    Route::post('/reopen-life-values/{userId}', [LifeValuesController::class, 'reopenForStudent'])->name('superadmin.reopen-life-values');

    // Student Test Results
    Route::get('/student-riasec/{id}/{result_id?}', [SuperAdminController::class, 'viewStudentRiasec'])->name('superadmin.student-riasec');
    Route::get('/student-life-values/{id}/{result_id?}', [SuperAdminController::class, 'getLifeValuesResult'])->name('superadmin.student-life-values');

    // Activity Log
    Route::get('/activity-log', [SuperAdminController::class, 'activityLog'])->name('superadmin.activity-log');
});

    // Admin Routes
    Route::middleware(['auth', 'preventBackHistory', 'role:admin'])->group(function () {
        Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        // Show additional information for a student
        Route::get('admin/students/{id}/additional-info', [AdminController::class, 'getAdditionalInfo'])->name('admin.students.additional-info');
        // Profile Management
        Route::get('/admin/update-profile', [AdminController::class, 'profile'])   ->name('admin.update-profile');
        Route::put('/admin/update-profile/{user}', [AdminController::class, 'updateProfile'])->name('admin.update-profile.update');

        // Student Accounts Management
        Route::get('/admin/student-profile', [AdminController::class, 'studentProfile'])->name('admin.student-profile');
        Route::get('/admin/student-accounts', [AdminController::class, 'studentAccounts'])->name('admin.student-accounts');
        Route::patch('/users/{user}/toggleStatus', [AdminController::class, 'toggleStatus'])->name('admin.users.toggleStatus');

        // Fetch curriculum chart data
       Route::get('/admin/students/curriculum-chart-data', [AdminController::class, 'getCurriculumChartData']) ->name('admin.students.curriculum-chart-data');
        // Fetch data for charts
        Route::get('/students/chart-data', [AdminController::class, 'getStudentChartData'])->name('admin.students.chart-data');
        // Fetch data for testing
        Route::get('/admin/dashboard-stats', [AdminController::class, 'getDashboardStats'])->name('admin.dashboard.stats');

        //view test result
        Route::get('/dashboard/riasec-stats', [DashboardController::class, 'getRiasecStats'])->name('dashboard.riasec.stats');
        Route::get('/dashboard/lifevalues-stats', [DashboardController::class, 'getLifeValuesStats'])->name('dashboard.lifevalues.stats');

          // ✅ Update Student Info
        Route::post('/admin/update-student-info/{id}', [App\Http\Controllers\AdminController::class, 'updateStudentInfo'])
    ->name('admin.update-student-info');

        // ✅ Update Archived Student Info
        Route::post('/admin/update-archived-student-info/{id}', [App\Http\Controllers\AdminArchivedStudentController::class, 'updateArchivedStudentInfo'])
    ->name('admin.update-archived-student-info');

        Route::get('admin/archived-student-data', [AdminArchivedStudentController::class, 'studentData'])
            ->name('admin.archived-student-data');

        Route::get('admin/archived-files-data', [AdminArchivedStudentController::class, 'archivedFiles'])
            ->name('admin.archived-files-data');

        Route::get('admin/archived-students/school-year/{schoolYearId}', [AdminArchivedStudentController::class, 'showArchivedStudents'])
            ->name('admin.archived.student.ajax');

        Route::get('admin/archived-students/{id}', [AdminArchivedStudentController::class, 'getArchivedStudent'])
            ->name('admin.archived.student.view');

        Route::post('admin/restore-archived-student/{id}', [AdminArchivedStudentController::class, 'restoreArchivedStudent'])
            ->name('admin.restore-archived-student');



            
        // View Student Test Result
        // Admin view specific student's test results
        Route::get('/admin/test-results', [AdminController::class, 'viewTestResults'])->name('admin.test-results');

        // Manage Test
        Route::get('/admin/manage-test', [AdminController::class, 'manageTest'])->name('admin.manage-test');
        Route::post('/admin/manage-test/toggle', [AdminController::class, 'toggleTest'])->name('admin.manage-test.toggle');

        // Admin: view student's RIASEC result page
        Route::get('/admin/student-riasec/{id}/{result_id?}', [AdminController::class, 'viewStudentRiasec'])->name('admin.student-riasec');
        Route::post('/admin/reopen-riasec/{userId}', [RiasecController::class, 'reopenForStudent'])->name('admin.reopen-riasec');
        Route::post('/admin/reopen-life-values/{userId}', [LifeValuesController::class, 'reopenForStudent'])->name('admin.reopen-life-values');
        // Admin: view student's Life Values result page
        Route::get('/admin/student-life-values/{id}/{result_id?}', [AdminController::class, 'getLifeValuesResult'])->name('admin.student-life-values');

        // Activity Log
        Route::get('/admin/activity-log', [AdminController::class, 'activityLog'])->name('admin.activity-log');

    });
    // Student Routes
    Route::middleware(['auth', 'role:student'])->group(function () {
        Route::get('/student/dashboard', [StudentController::class, 'dashboard'])->name('student.dashboard');

        // Profile Management
        Route::get('/student/update-profile', [StudentController::class, 'profile'])   ->name('student.update-profile');
        Route::put('/student/update-profile/{user}', [StudentController::class, 'updateProfile'])->name('student.update-profile.update');
        
        // Additional Info Page
        Route::get('/student/additional-info', [AdditionalInformationController::class, 'additionalInfo'])->name('student.additional-info');

        //Store Additional Info
        Route::post('/student/additional-info/store', [AdditionalInformationController::class, 'store'])->name('student.additional-info.store');

    // View Additional Info
    Route::get('/student/view-additional-info', [StudentController::class, 'viewAdditionalInfo'])->name('student.view-additional-info');
    // JSON endpoint for popup view (student)
    Route::get('/student/additional-info/json', [StudentController::class, 'getAdditionalInfoJson'])->name('student.additional-info.json');
        Route::view('/agreements', 'modals.agreements')->name('agreements');

        Route::post('/student/check-lrn', [AdditionalInformationController::class, 'checkLrn'])->name('student.check-lrn');

        // Download profile picture
        Route::get('/student/download-profile-picture', [AdditionalInformationController::class, 'downloadProfilePicture'])->name('student.download-profile-picture');

        //student testing page
        Route::get('/student/testingdash', [StudentController::class, 'testing'])
        ->name('student.testingdash');
 
        // Riasec Routes
        Route::get('/student/riasec', [RiasecController::class, 'index'])
        ->name('testing.riasec');

        Route::post('/riasec/save', [RiasecController::class, 'store'])
        ->name('riasec.save');



        // Life Values Inventory Route
        Route::get('/student/life-values-inventory', [LifeValuesController::class, 'index'])
        ->name('testing.life-values-inventory');

        Route::post('/life-values-submit/save', [LifeValuesController::class, 'store'])
            ->name('life-values-submit.save');
        


    });

require __DIR__.'/auth.php';

