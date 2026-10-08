<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\CertificateController;

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
Route::get('/registration/step1', [RegistrationController::class, 'showStep1'])->name('registration.step1');
Route::post('/registration/step1', [RegistrationController::class, 'saveStep1'])->name('registration.step1.save');
Route::post('/registration/step-2', [RegistrationController::class, 'saveStep1']);
Route::post('/registration/step2', [RegistrationController::class, 'saveStep1'])->name('registration.step2');
Route::get('/registration/step2', [RegistrationController::class, 'showStep2'])->name('registration.step2.show');
Route::get('/registration/step-2', [RegistrationController::class, 'showStep2']);
Route::post('/registration/submit', [RegistrationController::class, 'store'])->name('registration.submit');

Route::post('/notifications/announcements/{id}/read', [UserController::class, 'markAnnouncementRead'])->middleware('auth')->name('notifications.announcement.read');

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
    Route::put('/admin/course/{courseId}', [UserController::class, 'updateCourse'])->name('admin.course.update');
    Route::put('/admin/course/{id}', [UserController::class, 'updateCourse']);
    Route::delete('/admin/course/{id}', [UserController::class, 'destroy']);
    Route::get('/admin/course/{courseId}/content', [UserController::class, 'getCourseContent'])->name('admin.course.content');
    Route::post('/admin/course/{courseId}/assign-trainer', [UserController::class, 'assignTrainer'])->name('admin.course.assignTrainer');
    Route::post('/admin/course/{courseId}/remove-trainer', [UserController::class, 'removeTrainer'])->name('admin.course.removeTrainer');

    // Facilities
    Route::post('/admin/facility/save', [UserController::class, 'saveFacility'])->name('admin.facility.save');
    Route::post('/admin/facility/delete', [UserController::class, 'deleteFacility']);

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

    // Certificates (Moved inside admin group with correct /admin prefix)
    Route::post('/admin/certificate/store', [CertificateController::class, 'store'])->name('admin.certificate.store');
    Route::delete('/admin/certificate/{id}', [CertificateController::class, 'destroy'])->name('admin.certificate.destroy');
    Route::patch('/admin/certificate/{id}/toggle-status', [CertificateController::class, 'toggleStatus'])->name('admin.certificate.toggle-status');
});

// ── SUPERADMIN ROUTES ───────────────────────────────────

Route::middleware("super_admin")->group(function () {

    Route::get('/superadmin', function () {
        return view('superadmin.dashboard');
    })->name('superadmin.dashboard');

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
    Route::get("/trainer/students", [UserController::class, "trainerStudents"])->name("trainer.students");
    Route::post("/trainer/hands-on-evaluation", [UserController::class, "storeHandsOnEvaluation"])->name("trainer.hands-on.store");
    Route::get("/trainer/schedule", [UserController::class, "trainerSchedule"])->name("trainer.schedule");
    Route::get("/trainer/profile", [UserController::class, "trainerProfile"])->name("trainer.profile");
    Route::post("/trainer/profile/update", [UserController::class, "trainerProfileUpdate"])->name("trainer.profile.update");
    Route::post("/trainer/profile/password", [UserController::class, "trainerProfilePassword"])->name("trainer.profile.password");
    Route::get("/trainer/attendance", [UserController::class, "attendance"])->name("trainer.attendance");
    Route::post("/trainer/attendance", [UserController::class, "storeAttendance"])->name("trainer.attendance.store");
    Route::get("/trainer/course/{id}/preview", [UserController::class, "trainerCoursePreview"])->name("trainer.course.preview");
    Route::post("/trainer/course/{id}/objectives", [UserController::class, "updateCourseObjectives"])->name("trainer.course.objectives");

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
    Route::get('/student/announcements', [UserController::class, 'studentAnnouncements'])->name('student.announcements');
    Route::post("/student/module/{id}/complete", [UserController::class, "markModuleDone"])->name("student.module.complete");
});