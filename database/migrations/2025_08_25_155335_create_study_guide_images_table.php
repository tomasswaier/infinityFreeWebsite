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
        Schema::create('study_guide_images', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->timestamps();
            $table->string('filename');
            $table->unsignedBigInteger('study_guide_section_data_id');
            $table->foreign('study_guide_section_data_id')->references('id')->on('study_guide_section_data')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('study_guide_images');
    }
};
