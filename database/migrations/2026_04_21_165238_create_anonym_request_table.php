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
        Schema::create('anonym_requests', function (Blueprint $table) {
            $table->id('id')->primary();
            //source= any kind of lead as to where the request is coming from so it can be tests,question, subjects...
            $table->text('source')->nullable();
            $table->text('text');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anonym_requests');
    }
};
