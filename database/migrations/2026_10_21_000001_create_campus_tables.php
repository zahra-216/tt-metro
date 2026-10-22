<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $t) {
            $t->id();
            $t->string('key')->unique();
            $t->text('value')->nullable();
            $t->timestamps();
        });

        Schema::create('pages', function (Blueprint $t) {
            $t->id();
            $t->string('slug')->unique();
            $t->string('title');
            $t->string('subtitle')->nullable();
            $t->string('hero_image')->nullable();
            $t->longText('content')->nullable();
            $t->string('meta_description')->nullable();
            $t->timestamps();
        });

        Schema::create('stats', function (Blueprint $t) {
            $t->id();
            $t->string('value');
            $t->string('suffix')->nullable();
            $t->string('label');
            $t->unsignedInteger('sort_order')->default(0);
            $t->boolean('is_active')->default(true);
            $t->timestamps();
        });

        Schema::create('faculties', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('slug')->unique();
            $t->string('icon')->nullable();
            $t->text('summary')->nullable();
            $t->longText('description')->nullable();
            $t->string('image')->nullable();
            $t->unsignedInteger('sort_order')->default(0);
            $t->boolean('is_active')->default(true);
            $t->timestamps();
        });

        Schema::create('programmes', function (Blueprint $t) {
            $t->id();
            $t->foreignId('faculty_id')->nullable()->constrained()->nullOnDelete();
            $t->string('title');
            $t->string('slug')->unique();
            $t->string('level')->default('Diploma'); // Diploma, HND, Degree, Top-up, Masters, Other
            $t->string('duration')->nullable();
            $t->string('awarding_body')->nullable();
            $t->json('requirements')->nullable();
            $t->string('brochure')->nullable();
            $t->text('summary')->nullable();
            $t->longText('content')->nullable();
            $t->string('image')->nullable();
            $t->boolean('is_featured')->default(false);
            $t->boolean('is_active')->default(true);
            $t->unsignedBigInteger('wp_id')->nullable()->index();
            $t->timestamps();
        });

        Schema::create('events', function (Blueprint $t) {
            $t->id();
            $t->string('title');
            $t->string('slug')->unique();
            $t->date('starts_on')->nullable();
            $t->date('ends_on')->nullable();
            $t->string('location')->nullable();
            $t->text('excerpt')->nullable();
            $t->longText('content')->nullable();
            $t->string('image')->nullable();
            $t->boolean('is_active')->default(true);
            $t->unsignedBigInteger('wp_id')->nullable()->index();
            $t->timestamps();
        });

        Schema::create('posts', function (Blueprint $t) {
            $t->id();
            $t->string('title');
            $t->string('slug')->unique();
            $t->text('excerpt')->nullable();
            $t->longText('content')->nullable();
            $t->string('image')->nullable();
            $t->timestamp('published_at')->nullable();
            $t->boolean('is_active')->default(true);
            $t->timestamps();
        });

        Schema::create('gallery_albums', function (Blueprint $t) {
            $t->id();
            $t->string('title');
            $t->string('slug')->unique();
            $t->text('description')->nullable();
            $t->string('cover_image')->nullable();
            $t->unsignedInteger('sort_order')->default(0);
            $t->boolean('is_active')->default(true);
            $t->timestamps();
        });

        Schema::create('gallery_items', function (Blueprint $t) {
            $t->id();
            $t->foreignId('gallery_album_id')->constrained()->cascadeOnDelete();
            $t->string('type')->default('image'); // image | video | youtube
            $t->string('path')->nullable();       // uploaded image / mp4
            $t->string('video_url')->nullable();  // YouTube / Vimeo link
            $t->string('caption')->nullable();
            $t->unsignedInteger('sort_order')->default(0);
            $t->timestamps();
        });

        Schema::create('team_members', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('position')->nullable();
            $t->string('group')->default('board'); // board, council, senate, staff
            $t->text('bio')->nullable();
            $t->string('photo')->nullable();
            $t->unsignedInteger('sort_order')->default(0);
            $t->boolean('is_active')->default(true);
            $t->timestamps();
        });

        Schema::create('testimonials', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('role')->nullable();
            $t->text('quote');
            $t->string('photo')->nullable();
            $t->unsignedInteger('sort_order')->default(0);
            $t->boolean('is_active')->default(true);
            $t->timestamps();
        });

        Schema::create('partners', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('logo')->nullable();
            $t->string('url')->nullable();
            $t->unsignedInteger('sort_order')->default(0);
            $t->boolean('is_active')->default(true);
            $t->timestamps();
        });

        Schema::create('student_life_items', function (Blueprint $t) {
            $t->id();
            $t->string('category')->default('facility'); // facility, scholarship, club, sport
            $t->string('title');
            $t->text('description')->nullable();
            $t->string('image')->nullable();
            $t->unsignedInteger('sort_order')->default(0);
            $t->boolean('is_active')->default(true);
            $t->timestamps();
        });

        Schema::create('downloads', function (Blueprint $t) {
            $t->id();
            $t->string('title');
            $t->string('file');
            $t->unsignedInteger('sort_order')->default(0);
            $t->timestamps();
        });

        Schema::create('applications', function (Blueprint $t) {
            $t->id();
            $t->string('full_name');
            $t->string('email');
            $t->string('phone');
            $t->string('nic')->nullable();
            $t->date('date_of_birth')->nullable();
            $t->string('highest_qualification')->nullable();
            $t->foreignId('programme_id')->nullable()->constrained()->nullOnDelete();
            $t->string('programme_other')->nullable();
            $t->text('message')->nullable();
            $t->string('status')->default('new'); // new, contacted, closed
            $t->timestamps();
        });

        Schema::create('contact_messages', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('email');
            $t->string('phone')->nullable();
            $t->string('subject')->nullable();
            $t->text('message');
            $t->boolean('is_read')->default(false);
            $t->timestamps();
        });
    }

    public function down(): void
    {
        foreach (['contact_messages', 'applications', 'downloads', 'student_life_items', 'partners', 'testimonials', 'team_members', 'gallery_items', 'gallery_albums', 'posts', 'events', 'programmes', 'faculties', 'stats', 'pages', 'settings'] as $table) {
            Schema::dropIfExists($table);
        }
    }
};

