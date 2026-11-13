<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Enquiries from the "Need advice?" box on programme pages (kept apart from the Contact page messages).
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('course_enquiries', function (Blueprint $t) {
            $t->id();
            $t->foreignId('programme_id')->nullable()->constrained()->nullOnDelete();
            $t->string('programme_title')->nullable(); // kept even if the programme is later deleted
            $t->string('name');
            $t->string('phone');
            $t->text('message');
            $t->boolean('is_read')->default(false);
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_enquiries');
    }
};
