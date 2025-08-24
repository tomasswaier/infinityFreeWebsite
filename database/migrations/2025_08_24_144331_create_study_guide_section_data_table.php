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
        Schema::create('study_guide_section_data', function (Blueprint $table) {
            $table->id()->primary();
            $table->json('data');
            $table->string('name');
            $table->unsignedBigInteger('study_guide_section_order_id');
            $table->foreign('study_guide_section_order_id')->references('id')->on('study_guide_section_order')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('study_guide_section_data');
    }
};
