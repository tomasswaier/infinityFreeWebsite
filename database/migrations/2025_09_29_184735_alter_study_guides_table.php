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
        Schema::table('study_guides', function (Blueprint $table) {
            $table->unsignedBigInteger('parent_study_guide_id')->nullable();
            $table->foreign('parent_study_guide_id')->references('id')->on('study_guides')->onDelete('set null');

            $table->uuid('origin_study_guide_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('study_guides', function (Blueprint $table) {
            $table->dropForeign(['parent_study_guide_id']);
            $table->dropColumn('parent_study_guide_id');
            $table->dropColumn('origin_study_guide_id');
        });
    }
};
