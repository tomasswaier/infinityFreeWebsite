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
        Schema::create('boolean_choice_options', function (Blueprint $table) {
            $table->unsignedBigInteger('boolean_choice_id');
            $table->foreign('boolean_choice_id')->references('id')->on('boolean_choice')->onDelete('cascade');
            $table->string('option_text');
            $table->boolean('is_correct');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('boolean_choice_options');
    }
};
