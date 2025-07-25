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
        Schema::create('options', function (Blueprint $table) {
            $table->id()->primary();
            $table->unsignedBigInteger('questions_id');
            $table->foreign('questions_id')->references('id')->on('questions')->onDelete('cascade');
            $table->json('data');
            $table->text('preceding_text')->nullable();
            $table->string('option_type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('options');
    }
};
