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
        Schema::create('anonym_request_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('anonym_requests_id');
            $table->foreign('anonym_requests_id')->references('id')->on('anonym_requests')->onDelete('cascade');
            $table->string('image_name');//should be just name or file name however I already named it like a retard before and won't have multiple naming conventions
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anonym_request_images');
    }
};
