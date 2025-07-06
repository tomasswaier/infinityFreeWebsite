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
        Schema::create('write_in', function (Blueprint $table) {
            $table->id()->primary();
            $table->unsignedBigInteger('options_id');
            $table->foreign('options_id')->references('id')->on('options')->onDelete('cascade');
            $table->string('correct_answer');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('write_in');
    }
};
