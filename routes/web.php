<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\TrainerController;
use App\Http\Controllers\OnsiteGradeController;
use App\Http\Middleware\SuperAdminMiddleware;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\FacilityResourceController;
use App\Http\Controllers\DatabaseBackupController;
use App\Http\Controllers\TraineeNotificationController;
use App\Http\Controllers\TrainerNotificationController;
use App\Http\Controllers\AdminNotificationController;



// ── PUBLIC ROUTES (walang login needed) ──────────────────────────────────────
Route::get('/', [UserController::class, 'index'])->name('index');
Route::get("/login", [UserController::class, "Login"])->name("Login");
Route::post("/login", [UserController::class, "LoginUser"])->name("LoginUser");
Route::get("/sign_up", [UserController::class, "SignupPage"])->name("SignupPage");
Route::post("/signup-form", [UserController::class, "SignUp"])->name("SignUp");
Route::get("/handle", [UserController::class, "handle"])->name("handle");
Route::get("/adminlogin", [UserController::class, "adminlogin"])->name("adminlogin");
Route::post("/Logout", [UserController::class, "Logout"])->name("Logout");
Route::get("/about", [UserController::class, "landingAbout"])->name("landing.about");
Route::get("/contact-us", [UserController::class, "landingContact"])->name("landing.contact");
Route::post("/contact-us", [UserController::class, "landingContactSend"])->name("landing.contact.send");
Route::get("/our-courses/{id}", [UserController::class, "landingCourseDetail"])->name("landing.course.detail");

// Sign Up Email OTP Verification
Route::get('/signup/verify-otp', [UserController::class, 'showSignupOtp'])
    ->name('SignupOtpPage');

Route::post('/signup/verify-otp', [UserController::class, 'verifySignupOtp'])
    ->name('VerifySignupOtp');

Route::post('/signup/resend-otp', [UserController::class, 'resendSignupOtp'])
    ->name('ResendSignupOtp');

// Email Verification
Route::get('/email/verify', function () {
    return view('student.verify_email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (\Illuminate\Foundation\Auth\EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect()->route('handle');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (\Illuminate\Http\Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('status', 'verification-link-sent');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// Forgot/Reset Password (public)
Route::get("/forgotpassword", [UserController::class, "ForgotPassword"])->name("ForgotPassword");
Route::post("/forgotpassword", [UserController::class, "SendResetLink"])->name("SendResetLink");
Route::get("/reset-password/{token}", [UserController::class, "ResetPasswordPage"])->name("ResetPasswordPage");
Route::post("/reset-password", [UserController::class, "ResetPassword"])->name("ResetPassword");

// First time login reset (auth only)
Route::get("/first-reset", [UserController::class, "firstResetPage"])->name("first.reset")->middleware("auth");
Route::post("/first-reset", [UserController::class, "firstResetSave"])->name("first.reset.save")->middleware("auth");

// ── REGISTRATION (public — TESDA Learner's Profile Form) ──────────────────────

Route::get('/admin/registrations/export/pdf', [
    RegistrationController::class,
    'exportAllPdf'
])->name('admin.registrations.export.pdf');

// Database Backup
Route::post(
    '/admin/database-backup',
    [DatabaseBackupController::class, 'download']
)->name('superadmin.database-backup');

Route::get('/registration/step1', [RegistrationController::class, 'showStep1'])->name('registration.step1');
Route::post('/registration/step1', [RegistrationController::class, 'saveStep1'])->name('registration.step1.save');
Route::post('/registration/step-2', [RegistrationController::class, 'saveStep1']);
Route::post('/registration/step2', [RegistrationController::class, 'saveStep1'])->name('registration.step2');
Route::get('/registration/step2', [RegistrationController::class, 'showStep2'])->name('registration.step2.show');
Route::get('/registration/step-2', [RegistrationController::class, 'showStep2']);
Route::post('/registration/submit', [RegistrationController::class, 'store'])->name('registration.submit');

// ── ADMIN ROUTES ──────────────────────────────────────────────────────────────
Route::middleware("admin")->group(function () {
    // Registrations
    Route::get('/admin/registrations/export/csv', [RegistrationController::class, 'exportCsv'])->name('admin.registrations.export');
    Route::get('/admin/registrations/{registration}/pdf', [RegistrationController::class, 'downloadPdf'])->name('admin.registrations.pdf');
    Route::get('/admin/registrations/{registration}', [RegistrationController::class, 'adminShow'])->name('admin.registrations.show');

    // Dashboard & Views
    Route::get('/admin1', [UserController::class, 'admin1'])->name('admin1');
    Route::get('/trainees', [UserController::class, 'trainees'])->name('trainees');

    // Trainers & Users
    Route::post('/admin/trainer/store', [UserController::class, 'storeTrainer'])->name('admin.trainer.store');
    Route::post('/admin/user/update', [UserController::class, 'updateUser'])->name('admin.user.update');
    Route::post('/admin/user/delete', [UserController::class, 'deleteUser']);

    // Courses & Trainers Assignment
    Route::post('/admin/course/store', [UserController::class, 'storeCourse']);
    Route::post(
    '/admin/course/{course}/duplicate',
    [\App\Http\Controllers\UserController::class, 'duplicateCourse']
)->name('admin.course.duplicate');
    Route::put('/admin/course/{courseId}', [UserController::class, 'updateCourse'])->name('admin.course.update');
    Route::delete('/admin/course/{id}', [UserController::class, 'destroy']);
    Route::get('/admin/course/{courseId}/content', [UserController::class, 'getCourseContent'])->name('admin.course.content');
    Route::post('/admin/course/{courseId}/assign-trainer', [UserController::class, 'assignTrainer'])->name('admin.course.assignTrainer');
    Route::post('/admin/course/{courseId}/remove-trainer', [UserController::class, 'removeTrainer'])->name('admin.course.removeTrainer');

    // Facilities
    Route::post('/admin/facility/save', [UserController::class, 'saveFacility'])->name('admin.facility.save');
    Route::post('/admin/facility/delete', [UserController::class, 'deleteFacility']);

    // Facility Learning Resources
Route::post(
    '/admin/facilities/{facility}/resources',
    [FacilityResourceController::class, 'store']
)->name('admin.facilities.resources.store');

Route::put(
    '/admin/facilities/{facility}/resources/{resource}',
    [FacilityResourceController::class, 'update']
)->name('admin.facilities.resources.update');

Route::delete(
    '/admin/facilities/{facility}/resources/{resource}',
    [FacilityResourceController::class, 'destroy']
)->name('admin.facilities.resources.destroy');

    // Modules
    Route::post("/admin/module", [UserController::class, "storeModule"])->name("admin.module.store");
    Route::match(['POST', 'DELETE'], "/admin/module/{id}", [UserController::class, "destroyModule"])->name("admin.module.destroy");

    // Quizzes
    Route::post("/admin/quiz", [UserController::class, "storeQuiz"])->name("admin.quiz.store");
    Route::match(['POST', 'DELETE'], "/admin/quiz/{id}", [UserController::class, "destroyQuiz"])->name("admin.quiz.destroy");

    // Quiz Questions
    Route::get("/admin/quiz/{quizId}/questions", [UserController::class, "getQuizQuestions"])->name("admin.quiz.questions");
    Route::post("/admin/quiz-question", [UserController::class, "storeQuizQuestion"])->name("admin.quiz.question.store");
    Route::match(['POST', 'DELETE'], "/admin/quiz-question/{id}", [UserController::class, "destroyQuizQuestion"])->name("admin.quiz.question.destroy");

    // Announcements
    Route::get("/admin/announcements", [UserController::class, "admin1"])->name("admin.announcements");
    Route::post("/admin/announcement", [UserController::class, "storeAnnouncement"])->name("admin.announcement.store");
    Route::put("/admin/announcement/{id}", [UserController::class, "updateAnnouncement"])->name("admin.announcement.update");
    Route::delete("/admin/announcement/{id}", [UserController::class, "destroyAnnouncement"])->name("admin.announcement.destroy");
    Route::post("/admin/announcement/{id}/toggle", [UserController::class, "toggleAnnouncement"])->name("admin.announcement.toggle");

    // Landing Page
    Route::get('/admin/landingpage', [UserController::class, 'landingPage'])->name('admin.landingpage');
    Route::put('/admin/landingpage/section/{key}', [UserController::class, 'updateLandingSection'])->name('admin.landingpage.section.update');
    Route::post('/admin/landingpage/carousel', [UserController::class, 'storeCarouselSlide'])->name('admin.landingpage.carousel.store');
    Route::put('/admin/landingpage/carousel/{id}', [UserController::class, 'updateCarouselSlide'])->name('admin.landingpage.carousel.update');
    Route::delete('/admin/landingpage/carousel/{id}', [UserController::class, 'destroyCarouselSlide'])->name('admin.landingpage.carousel.destroy');

    Route::post('/admin/registrations/import', [
    \App\Http\Controllers\RegistrationImportController::class,
    'store'
])->name('admin.registrations.import');

Route::get('/admin/registrations/import/template', [
    \App\Http\Controllers\RegistrationImportController::class,
    'template'
])->name('admin.registrations.import.template');

    // Certificates (Moved inside admin group with correct /admin prefix)
    Route::post('/admin/certificate/store', [CertificateController::class, 'store'])->name('admin.certificate.store');
    Route::delete('/admin/certificate/{id}', [CertificateController::class, 'destroy'])->name('admin.certificate.destroy');
    Route::patch('/admin/certificate/{id}/toggle-status', [CertificateController::class, 'toggleStatus'])->name('admin.certificate.toggle-status');
    Route::patch(
    '/admin/certificate/{id}/release',
    [CertificateController::class, 'release']
)->name('admin.certificate.release');
});

Route::get(
    '/admin/module/file/{id}/{filename?}',
    [UserController::class, 'viewModuleFile']
)->name('admin.module.file');

Route::middleware(['auth'])->prefix('admin')->name('admin.notifications.')->group(function () {
    Route::get('/notifications', [AdminNotificationController::class, 'index'])->name('index');
    Route::post('/notifications/{id}/read', [AdminNotificationController::class, 'markRead'])->name('read');
    Route::post('/notifications/read-all', [AdminNotificationController::class, 'markAllRead'])->name('read-all');
});

// ── TRAINER ROUTES ────────────────────────────────────────────────────────────
    Route::middleware("trainer")->group(function () {
    Route::get("/teacher", [UserController::class, "teacher"])->name("teacher");
    Route::get("/learner", [UserController::class, "learner"])->name("learner");
    Route::get("/trainer/courses", [UserController::class, "courses"])->name("trainer.courses");
    Route::get("/assessment", [UserController::class, "assessment"])->name("assessment");
    Route::get("/certificates", [UserController::class, "certificates"])->name("certificates");
    Route::post("/trainer/certificate", [UserController::class, "trainerCertificateUpload"])->name("trainer.certificate.upload");
    Route::get("/reports", [UserController::class, "reports"])->name("reports");
    Route::get("/settings", [UserController::class, "settings"])->name("settings");
    Route::get("/trainer/schedule", [UserController::class, "trainerSchedule"])->name("trainer.schedule");
    Route::post('/trainer/attendance', [UserController::class, 'storeAttendance'])
    ->name('trainer.attendance.store');
    
Route::get("/trainer/students", [TrainerController::class, "students"])
    ->name("trainer.students");

    Route::get("/trainer/profile", [UserController::class, "trainerProfile"])->name("trainer.profile");
    Route::post("/trainer/profile/update", [UserController::class, "trainerProfileUpdate"])->name("trainer.profile.update");
    Route::post("/trainer/profile/password", [UserController::class, "trainerProfilePassword"])->name("trainer.profile.password");
    Route::get("/trainer/course/{id}/preview", [UserController::class, "trainerCoursePreview"])->name("trainer.course.preview");
    Route::post("/trainer/course/{id}/objectives", [UserController::class, "updateCourseObjectives"])->name("trainer.course.objectives");

    
    // Trainer Notifications
    Route::post(
        '/trainer/notifications/{notification}/read',
        [TrainerNotificationController::class, 'markRead']
    )->name('trainer.notifications.read');

    Route::post(
        '/trainer/notifications/read-all',
        [TrainerNotificationController::class, 'markAllRead']
    )->name('trainer.notifications.read-all');


    // Trainer course content
    Route::get("/trainer/course/{courseId}/content", [UserController::class, "getCourseContent"])->name("trainer.course.content");
    Route::post("/trainer/module", [UserController::class, "storeModule"])->name("trainer.module.store");
    Route::match(['POST', 'DELETE'], "/trainer/module/{id}", [UserController::class, "destroyModule"])->name("trainer.module.destroy");
    Route::post("/trainer/quiz", [UserController::class, "storeQuiz"])->name("trainer.quiz.store");
    Route::match(['POST', 'DELETE'], "/trainer/quiz/{id}", [UserController::class, "destroyQuiz"])->name("trainer.quiz.destroy");
    Route::get("/trainer/quiz/{quizId}/questions", [UserController::class, "getQuizQuestions"])->name("trainer.quiz.questions");
    Route::post("/trainer/quiz-question", [UserController::class, "storeQuizQuestion"])->name("trainer.quiz.question.store");
    Route::match(['POST', 'DELETE'], "/trainer/quiz-question/{id}", [UserController::class, "destroyQuizQuestion"])->name("trainer.quiz.question.destroy");
    Route::post("/trainer/course/{id}/description", [UserController::class, "updateCourseDescription"])->name("trainer.course.description");

    Route::get('/trainer/attendance', [
    \App\Http\Controllers\TrainerController::class,
    'attendance'
])->middleware('trainer')->name('trainer.attendance');
});

// ── STUDENT ROUTES ────────────────────────────────────────────────────────────
Route::middleware("student")->group(function () {
    Route::get("/homepage", [UserController::class, "homepage"])->name("homepage");
    Route::get("/courses", [UserController::class, "allCourses"])->name("all.courses");
    Route::get("/courses/{id}", [UserController::class, "courseDetail"])->name("course.detail");
    Route::post("/courses/{id}/enroll", [UserController::class, "enroll"])->name("course.enroll");
    Route::get("/student/about", [UserController::class, "about"])->name("about");
    Route::get("/contact", [UserController::class, "contact"])->name("contact");
    Route::post("/contact", [UserController::class, "sendContact"])->name("contact.send");
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
    Route::get("/student/quiz/{quizId}", [UserController::class, "getQuizForStudent"])->name("student.quiz.get");
    Route::post("/student/quiz/submit", [UserController::class, "submitQuiz"])->name("student.quiz.submit");
    Route::get("/student/profile", [UserController::class, "studentProfile"])->name("student.profile");
    Route::post("/student/profile/update", [UserController::class, "studentProfileUpdate"])->name("student.profile.update");
    Route::post("/student/profile/password", [UserController::class, "studentProfilePassword"])->name("student.profile.password");
    Route::get("/student/modules", [UserController::class, "studentModules"])->name("student.modules");

    // Trainee Notifications
Route::post(
    '/student/notifications/{announcement}/read',
    [TraineeNotificationController::class, 'markRead']
)->middleware('auth')->name('student.notifications.read');


// Student Comment Notifications


Route::post(
    '/student/trainer-notifications/{trainerAnnouncement}/read',
    [TraineeNotificationController::class, 'markTrainerRead']
)->middleware('auth')->name('student.trainer-notifications.read');
Route::post(
    '/student/comment-notifications/{notification}/read',
    [TraineeNotificationController::class, 'markCommentRead']
)->name('student.comment-notifications.read');

Route::post(
    '/student/comment-notifications/{notification}/read',
    [TraineeNotificationController::class, 'markCommentRead']
)->middleware('auth')->name('student.comment-notifications.read');


    Route::middleware(['web', 'student'])->group(function () {
    Route::get('/student/grades', [UserController::class, 'studentGrades'])
        ->name('student.grades');
});
    Route::get('/student/announcements', function () {
    $studentId = auth()->id();

    // Get courses enrolled in by the current student.
    $courseIds = DB::table('enrollment_tbls')
        ->where('user_id', $studentId)
        ->whereIn('status', ['active', 'completed'])
        ->pluck('course_id');

    // Fetch announcements posted by trainers for those courses.
    $announcements = DB::table('trainer_announcements as ta')
        ->join('course_tbls as c', 'c.id', '=', 'ta.course_id')
        ->join('user_tbls as u', 'u.id', '=', 'ta.trainer_id')
        ->whereIn('ta.course_id', $courseIds)
        ->where('ta.target_audience', 'all_students')
        ->select(
            'ta.id',
            'ta.course_id',
            'ta.content',
            'ta.target_audience',
            'ta.created_at',
            'c.title as course_title',
            'u.firstname as trainer_firstname',
            'u.lastname as trainer_lastname'
        )
        ->orderByDesc('ta.created_at')
        ->get();

    return view('student.announcements', compact('announcements'));
})->name('student.announcements');

Route::post('/student/announcements/{announcementId}/comments', function (
    \Illuminate\Http\Request $request,
    $announcementId
) {
    $validated = $request->validate([
        'comment' => ['required', 'string', 'max:2000'],
    ]);

    $studentId = auth()->id()
        ?? session('student_id')
        ?? session('user_id')
        ?? session('id');

    if (!$studentId) {
        return response()->json([
            'success' => false,
            'message' => 'Your student session was not recognized. Please log in again.',
        ], 401);
    }

    $student = \Illuminate\Support\Facades\DB::table('user_tbls')
        ->where('id', $studentId)
        ->first();

    if (!$student) {
        return response()->json([
            'success' => false,
            'message' => 'The current student account could not be found.',
        ], 401);
    }

    $announcement = \Illuminate\Support\Facades\DB::table('trainer_announcements')
        ->where('id', $announcementId)
        ->first();

    if (!$announcement) {
        return response()->json([
            'success' => false,
            'message' => 'Announcement not found.',
        ], 404);
    }

    $isEnrolled = \Illuminate\Support\Facades\DB::table('enrollment_tbls')
        ->where('user_id', $studentId)
        ->where('course_id', $announcement->course_id)
        ->exists();

    if (!$isEnrolled) {
        return response()->json([
            'success' => false,
            'message' => 'You must be enrolled in this course to comment.',
        ], 403);
    }

    $commentText = trim(strip_tags($validated['comment']));

    if ($commentText === '') {
        return response()->json([
            'success' => false,
            'message' => 'Please enter a valid comment.',
        ], 422);
    }

    $now = now();

    $commentId = \Illuminate\Support\Facades\DB::table('announcement_comments')
        ->insertGetId([
            'announcement_id' => $announcement->id,
            'user_id' => $studentId,
            'comment' => $commentText,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

    $firstName = trim($student->firstname ?? '');
    $lastName = trim($student->lastname ?? '');
    $authorName = trim($firstName . ' ' . $lastName) ?: 'Student';

    $nameParts = preg_split('/\s+/', $authorName, -1, PREG_SPLIT_NO_EMPTY);
    $initials = collect($nameParts)
        ->take(2)
        ->map(fn ($part) => mb_substr($part, 0, 1))
        ->implode('');

    return response()->json([
        'success' => true,
        'message' => 'Comment saved successfully.',
        'comment_id' => $commentId,
        'comment' => $commentText,
        'author_name' => $authorName,
        'initials' => $initials ?: 'S',
        'time' => $now->format('g:i A'),
    ], 201);
})->name('student.announcements.comments.store');
    Route::post("/student/module/{id}/complete", [UserController::class, "markModuleDone"])->name("student.module.complete");
});

Route::get('/student/certificates', [CertificateController::class, 'studentIndex'])
    ->name('student.certificates');


Route::post('/trainer/course/{course}/appearance', [TrainerController::class, 'updateAppearance'])
    ->name('trainer.course.appearance')
    ->middleware(['auth']);
    
    Route::middleware(['auth'])->group(function () {
    Route::post('/trainer/course/{id}/announcements', [TrainerController::class, 'storeAnnouncement'])
    ->name('trainer.course.announcements.store');
    Route::put('/trainer/course/announcements/{id}', [TrainerController::class, 'updateAnnouncement'])
    ->name('trainer.course.announcements.update');
Route::delete('/trainer/course/announcements/{id}', [TrainerController::class, 'destroyAnnouncement'])
    ->name('trainer.course.announcements.destroy');
    Route::post('/trainer/announcements/{id}/comments', [TrainerController::class, 'storeComment'])
    ->name('trainer.announcements.comments.store');

    Route::post('trainer/course/{id}/modules', [TrainerController::class, 'storeModule'])
    ->name('trainer.course.modules.store');

   Route::match(['delete', 'post'], 'trainer/modules/{id}', [TrainerController::class, 'deleteModule'])
    ->name('trainer.modules.destroy');
});

Route::post("/trainer/quiz", [UserController::class, "storeQuiz"])
    ->name("trainer.quiz.store");

Route::match(['POST', 'DELETE'], "/trainer/quiz/{id}", [UserController::class, "destroyQuiz"])
    ->name("trainer.quiz.destroy");

Route::get("/trainer/quiz/{id}/edit", [UserController::class, "editQuiz"])
    ->name("trainer.quiz.edit");

Route::put("/trainer/quiz/{id}", [UserController::class, "updateQuiz"])
    ->name("trainer.quiz.update");

  Route::post('/trainer/course/{id}/syllabus', [TrainerController::class, 'updateSyllabus'])
    ->name('trainer.course.syllabus.update');

    Route::delete('/trainer/course/{id}/syllabus', [App\Http\Controllers\TrainerController::class, 'deleteSyllabus'])
    ->name('trainer.course.syllabus.delete');

    Route::middleware(['web', 'trainer'])->prefix('trainer')->name('trainer.')->group(function () {
    // Activity management
    Route::post('/onsite-activities/store', [OnsiteGradeController::class, 'storeActivity'])->name('onsite-activities.store');
    Route::delete('/onsite-activities/{activity}', [OnsiteGradeController::class, 'destroyActivity'])->name('onsite-activities.destroy');

    // Grade encoding
    Route::get('/onsite-activities/{activity}/grades', [OnsiteGradeController::class, 'getGrades'])->name('onsite-activities.grades');
    Route::post('/onsite-grades/store', [OnsiteGradeController::class, 'storeGrades'])->name('onsite-grades.store');

});

Route::middleware(['auth', \App\Http\Middleware\SuperAdminMiddleware::class])
    ->prefix('superadmin')
    ->name('superadmin.')
    ->group(function () {
        Route::get('/dashboard', [
            \App\Http\Controllers\SuperAdminController::class,
            'dashboard',
        ])->name('dashboard');
    });