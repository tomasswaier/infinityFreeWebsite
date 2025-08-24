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
        Schema::create('study_guide_section_order', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('order');
            $table->unsignedBigInteger('study_guide_id');
            $table->foreign('study_guide_id')->references('id')->on('study_guides')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('study_guide_section_order');
    }
};
