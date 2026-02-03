<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('media')) {
            Schema::create('media', function (Blueprint $table) {
                $table->id();
                $table->string('disk')->default('database');
                $table->string('directory')->default('uploads');
                $table->string('filename');
                $table->string('extension');
                $table->string('mime_type');
                $table->string('aggregate_type')->default('image');
                $table->integer('size')->unsigned();
                $table->text('data'); // This stores the file as base64
                $table->morphs('model');
                $table->timestamps();

                $table->index(['model_type', 'model_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
