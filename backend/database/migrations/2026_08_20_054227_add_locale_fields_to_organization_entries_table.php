<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('organization_entries', function (Blueprint $table) {
            $table->text('description_id')->nullable()->after('description');
            $table->text('description_en')->nullable()->after('description_id');
        });

        DB::table('organization_entries')->update([
            'description_id' => DB::raw('description'),
        ]);

        Schema::table('organization_entries', function (Blueprint $table) {
            $table->dropColumn(['description']);
        });
    }

    public function down(): void
    {
        Schema::table('organization_entries', function (Blueprint $table) {
            $table->text('description')->nullable();
        });

        DB::table('organization_entries')->update([
            'description' => DB::raw('description_id'),
        ]);

        Schema::table('organization_entries', function (Blueprint $table) {
            $table->dropColumn(['description_id', 'description_en']);
        });
    }
};
