<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Populate slugs for existing colleges
        \Illuminate\Support\Facades\DB::table('colleges')->orderBy('id')->chunk(50, function ($colleges) {
            foreach ($colleges as $college) {
                if (is_null($college->slug)) {
                    $slug = \Illuminate\Support\Str::slug($college->name);
                    // Ensure uniqueness
                    $originalSlug = $slug;
                    $count = 1;
                    while (\Illuminate\Support\Facades\DB::table('colleges')->where('slug', $slug)->where('id', '!=', $college->id)->exists()) {
                        $slug = $originalSlug . '-' . $count++;
                    }
                    \Illuminate\Support\Facades\DB::table('colleges')->where('id', $college->id)->update(['slug' => $slug]);
                }
            }
        });

        // Add unique index on slug
        Schema::table('colleges', function (Blueprint $table) {
            $table->unique('slug', 'colleges_slug_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('colleges', function (Blueprint $table) {
            $table->dropUnique(['slug']);
        });

        // Set slugs to null in down
        Schema::table('colleges', function (Blueprint $table) {
            \Illuminate\Support\Facades\DB::table('colleges')->update(['slug' => null]);
        });
    }
};
