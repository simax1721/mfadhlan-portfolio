<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('skill_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->unsignedInteger('order')->default(0);
            $table->boolean('highlighted')->default(false);
            $table->timestamps();
        });

        Schema::table('skills', function (Blueprint $table) {
            $table->foreignId('skill_category_id')->nullable()->after('category')
                ->constrained('skill_categories')->nullOnDelete();
        });

        // Backfill: turn each distinct skills.category string into a row
        // here (ordered by that category's earliest skill), then point
        // skills at it via the new foreign key.
        $categories = DB::table('skills')
            ->select('category', DB::raw('MIN("order") as min_order'))
            ->groupBy('category')
            ->orderBy('min_order')
            ->get();

        foreach ($categories as $i => $row) {
            $id = DB::table('skill_categories')->insertGetId([
                'name' => $row->category,
                'order' => $i,
                'highlighted' => $row->category === 'AI-Assisted Development',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('skills')->where('category', $row->category)->update(['skill_category_id' => $id]);
        }

        Schema::table('skills', function (Blueprint $table) {
            $table->dropColumn('category');
        });
    }

    public function down(): void
    {
        Schema::table('skills', function (Blueprint $table) {
            $table->string('category')->nullable();
        });

        DB::table('skills')->update([
            'category' => DB::raw(
                '(select name from skill_categories where skill_categories.id = skills.skill_category_id)'
            ),
        ]);

        Schema::table('skills', function (Blueprint $table) {
            $table->dropConstrainedForeignId('skill_category_id');
        });

        Schema::dropIfExists('skill_categories');
    }
};
