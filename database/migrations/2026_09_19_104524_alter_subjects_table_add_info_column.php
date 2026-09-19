<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

//not writing this by hand
/**
 * MySQL can't roll back schema changes (no transactional DDL), so if a step fails
 * halfway the table is left half-changed. Every step below checks the current state
 * first, which makes both up() and down() safe to re-run after a failure.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Add the new json column
        if (! Schema::hasColumn('subjects', 'info')) {
            Schema::table('subjects', function (Blueprint $table) {
                $table->json('info')->nullable()->after('description');
                $table->unsignedBigInteger('views');
            });
        }

        // 2. Copy description -> info.content (only if description still exists)
        if (Schema::hasColumn('subjects', 'description')) {
            DB::table('subjects')
                ->select('id', 'description')
                ->orderBy('id')
                ->chunkById(100, function ($subjects) {
                    foreach ($subjects as $subject) {
                        DB::table('subjects')
                            ->where('id', $subject->id)
                            ->update([
                                'info' => json_encode(
                                    ['content' => $subject->description],
                                    JSON_UNESCAPED_UNICODE
                                ),
                            ]);
                    }
                });

            // 3. Drop the old column
            Schema::table('subjects', function (Blueprint $table) {
                $table->dropColumn('description');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 1. Bring description back (skip if a previous attempt already did)
        if (! Schema::hasColumn('subjects', 'description')) {
            Schema::table('subjects', function (Blueprint $table) {
                $table->text('description')->nullable();
            });
        }

        // 2. Copy info.content -> description, then remove info
        if (Schema::hasColumn('subjects', 'info')) {
            DB::table('subjects')
                ->select('id', 'info')
                ->orderBy('id')
                ->chunkById(100, function ($subjects) {
                    foreach ($subjects as $subject) {
                        $info = json_decode($subject->info ?? '', true);

                        DB::table('subjects')
                            ->where('id', $subject->id)
                            ->update(['description' => $info['content'] ?? null]);
                    }
                });

            Schema::table('subjects', function (Blueprint $table) {
                $table->dropColumn('info');
                $table->dropColumn('views');
            });
        }
    }
};
