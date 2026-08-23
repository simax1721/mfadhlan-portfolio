<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('skill_categories')
            ->where('name', 'AI-Assisted Development')
            ->update(['name' => 'Engineering Workflow']);
    }

    public function down(): void
    {
        DB::table('skill_categories')
            ->where('name', 'Engineering Workflow')
            ->update(['name' => 'AI-Assisted Development']);
    }
};
