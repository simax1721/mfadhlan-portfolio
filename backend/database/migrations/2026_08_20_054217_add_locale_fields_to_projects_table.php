<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->string('subtitle_id')->nullable()->after('subtitle');
            $table->string('subtitle_en')->nullable()->after('subtitle_id');
            $table->text('description_id')->nullable()->after('description');
            $table->text('description_en')->nullable()->after('description_id');
            $table->json('bullets_id')->nullable()->after('bullets');
            $table->json('bullets_en')->nullable()->after('bullets_id');
        });

        DB::table('projects')->update([
            'subtitle_id' => DB::raw('subtitle'),
            'description_id' => DB::raw('description'),
            'bullets_id' => DB::raw('bullets'),
        ]);

        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['subtitle', 'description', 'bullets']);
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->string('subtitle')->nullable();
            $table->text('description')->nullable();
            $table->json('bullets')->nullable();
        });

        DB::table('projects')->update([
            'subtitle' => DB::raw('subtitle_id'),
            'description' => DB::raw('description_id'),
            'bullets' => DB::raw('bullets_id'),
        ]);

        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn([
                'subtitle_id', 'subtitle_en',
                'description_id', 'description_en',
                'bullets_id', 'bullets_en',
            ]);
        });
    }
};
