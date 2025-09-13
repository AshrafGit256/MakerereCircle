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
        Schema::table('colleges', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('name');
        });

        // Populate slugs for existing colleges
        \Illuminate\Support\Facades\DB::table('colleges')->orderBy('id')->chunk(50, function ($colleges) {
            foreach ($colleges as $college) {
                $slug = \Illuminate\Support\Str::slug($college->name);
                // Ensure uniqueness
                $originalSlug = $slug;
                $count = 1;
                while (\Illuminate\Support\Facades\DB::table('colleges')->where('slug', $slug)->where('id', '!=', $college->id)->exists()) {
                    $slug = $originalSlug . '-' . $count++;
                }
                \Illuminate\Support\Facades\DB::table('colleges')->where('id', $college->id)->update(['slug' => $slug]);
            }
        });

        // Now add unique index
        Schema::table('colleges', function (Blueprint $table) {
            $table->unique('slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('colleges', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn('slug');
        });
    }
};
