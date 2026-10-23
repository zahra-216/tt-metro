<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('branches', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('address');
            $t->string('phone_1')->nullable();
            $t->string('phone_2')->nullable();
            $t->string('map_url')->nullable();
            $t->unsignedInteger('sort_order')->default(0);
            $t->boolean('is_active')->default(true);
            $t->timestamps();
        });

        $now = now();
        DB::table('branches')->insert([
            ['name' => 'Ampara Branch - Sammanthurai', 'address' => '249, Al-marjan Road, Sammanthurai 32200', 'phone_1' => '071 010 2093', 'phone_2' => null, 'map_url' => 'https://maps.app.goo.gl/rL8mnPjTYxSS8FLd7?g_st=aw', 'sort_order' => 1, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Batticaloa Branch', 'address' => '19, Saravana Road, Kallady, Batticaloa', 'phone_1' => '065 433 0015', 'phone_2' => '077 492 9121', 'map_url' => 'https://maps.app.goo.gl/vJkUP8bfqrn1ZsYJ9', 'sort_order' => 2, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('branches');
    }
};