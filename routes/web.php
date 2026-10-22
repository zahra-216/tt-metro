<?php

use App\Http\Controllers\FormController;
use App\Http\Controllers\SiteController;
use App\Models\Event;
use App\Models\Post;
use App\Models\Programme;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

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

// Addresses from the old WordPress site, kept alive so Google results, bookmarks and shared links still work.
Route::get('/courses/{slug}', fn (string $slug) => redirect(Programme::where('slug', $slug)->exists() ? route('programme', $slug) : route('academics'), 301));
Route::get('/event/{slug}', function (string $slug) {
    $clean = Str::slug(urldecode($slug)); // old event links sometimes carried emoji

    return redirect(Event::where('slug', $clean)->exists() ? route('event', $clean) : route('events'), 301);
});
Route::get('/{slug}', function (string $slug) {
    return Post::where('slug', $slug)->exists() ? redirect(route('post', $slug), 301) : abort(404);
})->where('slug', 'tt-metro-campus-introduces-new-diploma-in-information-technology');
foreach ([
    'about-us' => '/about', 'governing-council' => '/about', 'governing-council-2' => '/about', 'academic-senate' => '/about',
    'academic-management-staff' => '/about', 'our-affiliation-partners' => '/about', 'our-lecturers' => '/about',
    'our-authorized-centers' => '/about', 'contact-us' => '/contact', 'news' => '/events', 'home' => '/',
    'courses' => '/academics', 'diploma-courses' => '/academics', 'hnd-courses' => '/academics', 'hnd-programs' => '/academics',
    'brochures' => '/academics', 'scholarship' => '/student-life', 'facilities' => '/student-life',
] as $old => $new) {
    Route::redirect("/{$old}", $new, 301);
}
Route::get('/{page}', fn () => redirect(route('academics'), 301))
    ->where('page', '(diploma|hnd|degree)-programs-.+|undergraduate-programmes?-.+|faculty-.+');
Route::get('/lessons/{any}', fn () => redirect(route('academics'), 301))->where('any', '.*');
Route::get('/quizzes/{any}', fn () => redirect(route('academics'), 301))->where('any', '.*');
Route::get('/student-portal', fn () => redirect(setting('student_portal_url') ?: route('home'), 301));

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