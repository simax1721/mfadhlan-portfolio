<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            // Short "evidence line" proof-points shown under the Hero
            // tagline, e.g. "40+ REST API endpoints".
            $table->json('highlights_id')->nullable()->after('summary_en');
            $table->json('highlights_en')->nullable()->after('highlights_id');
        });
    }

    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn(['highlights_id', 'highlights_en']);
        });
    }
};
