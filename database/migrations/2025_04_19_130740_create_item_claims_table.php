<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemClaimsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('item_claims', function (Blueprint $table) {
            $table->bigIncrements('id');

            // Reference back to the post that was found
            $table->unsignedBigInteger('post_id')
                  ->comment('The post (found item) that this claim relates to');
            $table->foreign('post_id')
                  ->references('id')
                  ->on('posts')
                  ->onDelete('cascade');

            // Captured from the form:
            $table->string('full_name')
                  ->comment('Claimant’s full name');
            $table->string('contact_info')
                  ->comment('Phone number or email address');
            $table->text('description')->nullable()
                  ->comment('Any description or proof provided');

            // Timestamps to know when the claim was submitted
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('item_claims');
    }
}
