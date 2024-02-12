<?php

namespace App;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Website\CompanyController;
use App\Http\Controllers\Website\WebsiteController;
use App\Http\Controllers\Auth\SocialLoginController;
use App\Http\Controllers\GlobalController;
use App\Http\Controllers\Website\CandidateController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

// Authentication
if (!app()->runningInConsole()) {
    Auth::routes(['verify' => setting('email_verification')]);
} else {
    Auth::routes(['verify' => false]);
}

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    // return 123;
    $request->fulfill();

    if (auth('user')->user()->role == 'company') {
        return redirect()->route('company.dashboard', ['verified' => true]);
    } else {
        return redirect()->route('candidate.dashboard', ['verified' => true]);
    }
})->middleware(['auth', 'signed'])->name('verification.verify');

// Guest Routes
Route::controller(WebsiteController::class)->name('website.')->group(function () {
    Route::get('/', 'index')->name('home');
    Route::get('/about', 'about')->name('about');
    Route::get('/contact', 'contact')->name('contact');
    Route::get('/plans', 'pricing')->name('plan');
    Route::get('/plans/{label}', 'planDetails')->name('plan.details');
    Route::get('/faq', 'faq')->name('faq');
    Route::get('/terms-condition', 'termsCondition')->name('termsCondition');
    Route::get('/privacy-policy', 'privacyPolicy')->name('privacyPolicy');
    Route::get('/coming-soon', 'comingSoon')->name('comingsoon');
    Route::get('/careerjet/jobs', 'careerjetJobs')->name('careerjet.job');
    Route::get('/indeed/jobs', 'indeedJobs')->name('indeed.job');
    Route::get('/jobs', 'jobs')->name('job');
    Route::get('/jobs/{job:slug}', 'jobDetails')->name('job.details');
    Route::get('/jobs/{job:slug}/bookmark', 'toggleBookmarkJob')->name('job.bookmark')->middleware('user_active');
    Route::post('/jobs/apply', 'toggleApplyJob')->name('job.apply')->middleware('user_active');
    Route::get('/candidates', 'candidates')->name('candidate');
    Route::get('/candidates/{candidate:username}', 'candidateDetails')->name('candidate.details');
    Route::get('/candidate/profile/details', 'candidateProfileDetails')->name('candidate.profile.details');
    Route::get('/candidate/application/profile/details', 'candidateApplicationProfileDetails')->name('candidate.application.profile.details');
    Route::get('/candidates/download/cv/{resume}', 'candidateDownloadCv')->name('candidate.download.cv');
    Route::get('/employers', 'employees')->name('company');
    Route::get('/employers/{user:username}', 'employersDetails')->name('employe.details');
    Route::get('/posts', 'posts')->name('posts');
    Route::get('/post/{post:slug}', 'post')->name('post');
    Route::post('/comment/{post:slug}/add', 'comment')->name('comment');
    Route::post('/markasread/single/notification', 'markReadSingleNotification')->name('markread.notification');
    Route::post('/set/session', 'setSession')->name('set.session');
    Route::get('/selected/country', 'setSelectedCountry')->name('set.country');
    Route::get('/selected/country/remove', 'removeSelectedCountry')->name('remove.country');
    Route::get('job/autocomplete', 'jobAutocomplete')->name('job.autocomplete');
});

// Social Authentication
Route::get('/auth/{provider}/redirect', [SocialLoginController::class, 'redirect'])
    ->where('provider', 'google|facebook|github|twitter|linkedin')->name('social.login');

Route::get('/auth/{provider}/callback', [SocialLoginController::class, 'callback'])
    ->where('provider', 'google|facebook|github|twitter|linkedin');

// Authentiated Routes
Route::middleware('auth:user', 'verified')->group(function () {
    // Dashboard Route
    Route::get('/user/dashboard', [WebsiteController::class, 'dashboard'])->name('user.dashboard');

    Route::post('/user/notification/read', [WebsiteController::class, 'notificationRead'])->name('user.notification.read');

    // Candidate Routes
    Route::controller(CandidateController::class)->prefix('candidate')->middleware('candidate')->name('candidate.')->group(function () {
        Route::get('dashboard', 'dashboard')->name('dashboard');
        Route::get('applied-jobs', 'appliedjobs')->name('appliedjob');
        Route::get('bookmarks', 'bookmarks')->name('bookmark');
        Route::get('settings', 'setting')->name('setting');
        Route::put('settings/update', 'settingUpdate')->name('settingUpdate');
        Route::post('get/city', 'getCity')->name('getCity');
        Route::post('get/state', 'getState')->name('getState');
        Route::get('/all/notifications', 'allNotification')->name('allNotification');
        Route::get('/job/alerts', 'jobAlerts')->name('job.alerts');
        Route::post('/resume/store', 'resumeStore')->name('resume.store');
        Route::post('/resume/store/ajax', 'resumeStoreAjax')->name('resume.store.ajax');
        Route::post('/get/resume/ajax', 'getResumeAjax')->name('get.resume.ajax');
        Route::post('/resume/update', 'resumeUpdate')->name('resume.update');
        Route::delete('/resume/delete/{resume}', 'resumeDelete')->name('resume.delete');

        Route::post('/experiences/store', 'experienceStore')->name('experiences.store');
        Route::put('/experiences/update', 'experienceUpdate')->name('experiences.update');
        Route::delete('/experiences/{experience}', 'experienceDelete')->name('experiences.destroy');

        Route::post('/educations/store', 'educationStore')->name('educations.store');
        Route::put('/educations/update', 'educationUpdate')->name('educations.update');
        Route::delete('/educations/{education}', 'educationDelete')->name('educations.destroy');
    });

    // Company Routes
    Route::controller(CompanyController::class)->prefix('company')->middleware(['company', 'has_plan'])->name('company.')->group(function () {
        Route::middleware('company.profile')->group(function () {
            Route::get('dashboard', 'dashboard')->name('dashboard');
            Route::get('plans', 'plan')->name('plan')->middleware('user_active');
            Route::post('download/transaction/invoice/{transaction}', 'downloadTransactionInvoice')->name('transaction.invoice.download');
            Route::get('my-jobs', 'myjobs')->name('myjob')->withoutMiddleware('has_plan');
            Route::get('pending-edited-jobs', 'pendingEditedJobs')->name('pending.edited.jobs');
            Route::get('create/pay-per-job', 'payPerJob')->name('job.payPerJobCreate')->withoutMiddleware('has_plan');
            Route::post('/store/payper/job', 'storePayPerJob')->name('payperjob.store')->withoutMiddleware('has_plan');
            Route::get('create/job', 'createJob')->name('job.create')->middleware('check_plan')->middleware('user_active');
            Route::post('/store/job', 'storeJob')->name('job.store')->middleware('check_plan');
            Route::get('/job/payment', 'payPerJobPayment')->name('payperjob.payment')->withoutMiddleware('has_plan');
            Route::get('/promote/job/{job:slug}', 'showPromoteJob')->name('job.promote.show');
            Route::get('/promote/{job:slug}', 'jobPromote')->name('promote');
            Route::get('/clone/{job:slug}', 'jobClone')->name('clone');
            Route::post('/promote/job/{jobCreated}', 'promoteJob')->name('job.promote');
            Route::get('edit/{job:slug}/job', 'editJob')->name('job.edit')->withoutMiddleware('has_plan');
            Route::post('make/job/expire/{job}', 'makeJobExpire')->name('job.make.expire');
            Route::put('/update/{job:slug}/job', 'updateJob')->name('job.update')->withoutMiddleware('has_plan');
            Route::get('job/applications', 'jobApplications')->name('job.application');
            Route::put('applications/sync', 'applicationsSync')->name('application.sync');
            Route::post('applications/column/store', 'applicationColumnStore')->name('applications.column.store');
            Route::delete('applications/group/delete/{group}', 'applicationColumnDelete')->name('applications.column.delete');
            Route::put('applications/group/update', 'applicationColumnUpdate')->name('applications.column.update');
            Route::delete('delete/{job:id}/application', 'destroyApplication')->name('application.delete');
            Route::get('bookmarks', 'bookmarks')->name('bookmark');
            Route::get('settings', 'setting')->name('setting')->withoutMiddleware('has_plan');
            Route::put('settings/update', 'settingUpdateInformaton')->name('settingUpdateInformaton')->withoutMiddleware('has_plan');
            Route::get('/all/notifications', 'allNotification')->name('allNotification');
            Route::put('settings/update/contactinfo', 'settingUpdateContactInformaton')->name('settingUpdateContactInformaton');
            Route::put('settings/update/socialmedia', 'settingUpdateSocialMedia')->name('settingUpdateSocialMedia');
            // ====== appication group =======
            Route::post('applications/group/store', 'applicationsGroupStore')->name('applications.group.store');
            Route::put('applications/group/update/{group}', 'applicationsGroupUpdate')->name('applications.group.update');
            Route::delete('applications/group/destroy/{group}', 'applicationsGroupDestroy')->name('applications.group.destroy');
            // ====== appication group End=======
        });

        Route::post('/settings/statelist', 'getStateList')->name('getStateByCountry');
        Route::post('/settings/citylist', 'getCityList')->name('getCityByCountry');

        Route::post('/company/bookmark/{candidate}', 'companyBookmarkCandidate')->name('companybookmarkcandidate')->middleware('user_active');
        Route::get('account-progress', 'accountProgress')->name('account-progress')->withoutMiddleware('has_plan');
        Route::put('/profile/complete/{id}', 'profileCompleteProgress')->name('profile.complete')->withoutMiddleware('has_plan');
        Route::get('/bookmark/categories', 'bookmarkCategories')->name('bookmark.category.index');
        Route::post('/bookmark/categories/store', 'bookmarkCategoriesStore')->name('bookmark.category.store');
        Route::get('/bookmark/categories/edit/{category}', 'bookmarkCategoriesEdit')->name('bookmark.category.edit');
        Route::put('/bookmark/categories/update/{category}', 'bookmarkCategoriesUpdate')->name('bookmark.category.update');
        Route::delete('/bookmark/categories/destroy/{category}', 'bookmarkCategoriesDestroy')->name('bookmark.category.destroy');
        Route::post('send-email', 'sendEmailCandidate')->name('send.email');
    });
});


Route::get('/translated/texts',[GlobalController::class, 'fetchCurrentTranslatedText']);

Route::get('/lang/{lang}', function ($lang) {
    session()->put('set_lang', $lang);
    app()->setLocale($lang);

    return back();
});
