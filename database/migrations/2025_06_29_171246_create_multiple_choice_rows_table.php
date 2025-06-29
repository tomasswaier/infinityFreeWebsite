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
        Schema::create('multiple_choice_rows', function (Blueprint $table) {
            $table->id()->prumary();
            $table->timestamps();
            $table->unsignedBigInteger('multiple_choice_id');
            $table->foreign('multiple_choice_id')->references('id')->on('multiple_choice')->onDelete('cascade');
            $table->string('row_text');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('multiple_choice_rows');
    }
};
