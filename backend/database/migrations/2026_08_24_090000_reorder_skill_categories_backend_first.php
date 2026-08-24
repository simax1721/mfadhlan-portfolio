<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * "Engineering Workflow" was leading the Skills section, ahead of
     * "Backend" — undercutting the site's own "Backend / Fullstack
     * Developer" positioning. Reorders so core engineering skills lead.
     */
    public function up(): void
    {
        $order = [
            'Backend' => 0,
            'Frontend' => 1,
            'Database' => 2,
            'Tools' => 3,
            'Engineering Workflow' => 4,
        ];

        foreach ($order as $name => $position) {
            DB::table('skill_categories')
                ->where('name', $name)
                ->update(['order' => $position]);
        }
    }

    public function down(): void
    {
        $order = [
            'Engineering Workflow' => 0,
            'Backend' => 1,
            'Frontend' => 2,
            'Database' => 3,
            'Tools' => 4,
        ];

        foreach ($order as $name => $position) {
            DB::table('skill_categories')
                ->where('name', $name)
                ->update(['order' => $position]);
        }
    }
};
