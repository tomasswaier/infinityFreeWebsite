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
        Schema::create('one_from_many_options', function (Blueprint $table) {
            $table->unsignedBigInteger('one_from_many_id');
            $table->foreign('one_from_many_id')->references('id')->on('one_from_many')->onDelete('cascade');
            $table->boolean('is_correct');
            $table->string('option_text');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('one_from_many_options');
    }
};
