<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('chats', function (Blueprint $table) {
            $table->id(); // id (Primary key)

            $table->unsignedBigInteger('sender_id')->nullable();   // sender_id
            $table->unsignedBigInteger('receiver_id')->nullable(); // receiver_id

            $table->text('message')->nullable();      // message
            $table->string('file', 256)->nullable();  // file

            $table->tinyInteger('status')->default(0)
                ->comment('0: not read, 1: read'); // status with comment

            $table->integer('created_date')->nullable(); // integer timestamp
            $table->dateTime('created_at')->nullable();  // readable date
            $table->dateTime('updated_at')->nullable();  // readable date
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chats');
    }
};
