<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // The modules a student studies, semester by semester: [{"semester": 1, "title": "..."}, ...]
        Schema::table('programmes', function (Blueprint $table) {
            $table->json('modules')->nullable()->after('requirements');
        });
    }

    public function down(): void
    {
        Schema::table('programmes', function (Blueprint $table) {
            $table->dropColumn('modules');
        });
    }
};
