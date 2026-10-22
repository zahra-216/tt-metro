<?php

use App\Http\Controllers\FormController;
use App\Http\Controllers\SiteController;
use Illuminate\Support\Facades\Route;

Route::controller(SiteController::class)->group(function () {
    Route::get('/', 'home')->name('home');
    Route::get('/about', 'about')->name('about');
    Route::get('/academics', 'academics')->name('academics');
    Route::get('/programmes/{programme:slug}', 'programme')->name('programme');
    Route::get('/student-life', 'studentLife')->name('student-life');
    Route::get('/gallery', 'gallery')->name('gallery');
    Route::get('/gallery/{album:slug}', 'album')->name('album');
    Route::get('/events', 'events')->name('events');
    Route::get('/events/{event:slug}', 'event')->name('event');
    Route::get('/news/{post:slug}', 'post')->name('post');
    Route::get('/contact', 'contact')->name('contact');
    Route::get('/apply', 'apply')->name('apply');
});

Route::controller(FormController::class)->middleware('throttle:6,1')->group(function () {
    Route::post('/apply', 'apply')->name('apply.submit');
    Route::post('/contact', 'contact')->name('contact.submit');
});
if (app()->isLocal()) {
    // Preview the notification emails in the browser while designing them.
    Route::get('/dev/mail-preview/{type}', function (string $type) {
        $mailable = match ($type) {
            'contact' => new \App\Mail\ContactReceived(\App\Models\ContactMessage::latest()->first() ?? new \App\Models\ContactMessage(['name' => 'Test User', 'email' => 'test@example.com', 'phone' => '0771234567', 'subject' => 'Question about admissions', 'message' => "Hello,\nI would like to know the intake dates for the HND in IT.", 'created_at' => now()])),
            'application' => new \App\Mail\ApplicationReceived((\App\Models\Application::with('programme')->latest()->first()) ?? new \App\Models\Application(['full_name' => 'Test Student', 'email' => 'student@example.com', 'phone' => '0771234567', 'created_at' => now()])),
            default => abort(404),
        };

        return $mailable->render();
    });
}