<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->text('tagline_id')->nullable()->after('tagline');
            $table->text('tagline_en')->nullable()->after('tagline_id');
            $table->text('summary_id')->nullable()->after('summary');
            $table->text('summary_en')->nullable()->after('summary_id');
        });

        DB::table('profiles')->update([
            'tagline_id' => DB::raw('tagline'),
            'summary_id' => DB::raw('summary'),
        ]);

        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn(['tagline', 'summary']);
        });
    }

    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->string('tagline')->nullable();
            $table->text('summary')->nullable();
        });

        DB::table('profiles')->update([
            'tagline' => DB::raw('tagline_id'),
            'summary' => DB::raw('summary_id'),
        ]);

        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn(['tagline_id', 'tagline_en', 'summary_id', 'summary_en']);
        });
    }
};
