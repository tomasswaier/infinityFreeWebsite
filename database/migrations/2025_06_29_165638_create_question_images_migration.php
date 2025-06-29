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
        Schema::create('question_images_migration', function (Blueprint $table) {
            $table->unsignedBigInteger('questions_id');
            $table->foreign('questions_id')->references('id')->on('questions')->onDelete('cascade');
            $table->string('image_name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('question_images_migration');
    }
};
