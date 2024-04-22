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
        Schema::create('blogposts', function (Blueprint $table) {
            $table->id('post_id');
            $table->unsignedBigInteger('user_id');
            $table->string('title')->unique();
            $table->string('description');
            $table->string('img_url')->nullable();
            $table->string('post_category')->nullable();
            $table->string('read_time')->nullable();
            $table->text('content');
            $table->boolean('isPublished')->default(false);
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // Indexes
            $table->index('user_id'); // Simple index on user_id
            $table->index('isPublished'); // Simple index on user_id
            $table->index('post_category'); // Simple index on user_id
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blogposts');
    }
};
