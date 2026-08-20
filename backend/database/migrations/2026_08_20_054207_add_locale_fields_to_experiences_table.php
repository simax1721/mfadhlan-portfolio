<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('experiences', function (Blueprint $table) {
            $table->json('bullets_id')->nullable()->after('bullets');
            $table->json('bullets_en')->nullable()->after('bullets_id');
        });

        DB::table('experiences')->update([
            'bullets_id' => DB::raw('bullets'),
        ]);

        Schema::table('experiences', function (Blueprint $table) {
            $table->dropColumn(['bullets']);
        });
    }

    public function down(): void
    {
        Schema::table('experiences', function (Blueprint $table) {
            $table->json('bullets')->nullable();
        });

        DB::table('experiences')->update([
            'bullets' => DB::raw('bullets_id'),
        ]);

        Schema::table('experiences', function (Blueprint $table) {
            $table->dropColumn(['bullets_id', 'bullets_en']);
        });
    }
};
