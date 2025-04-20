<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // Keep for down method raw SQL
use Illuminate\Support\Facades\Log; // Keep for logging

class UpdateQuestionPapersTableForTopicYearSync extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Drop the topic_id column if it exists
        Schema::table('question_papers', function (Blueprint $table) {
            if (Schema::hasColumn('question_papers', 'topic_id')) {
                $table->dropColumn('topic_id');
            }
        });

        // Change year column type to date using Schema Builder
        // Requires doctrine/dbal package
        Schema::table('question_papers', function (Blueprint $table) {
             if (Schema::hasColumn('question_papers', 'year')) {
                $table->date('year')->nullable()->change(); // Change to date type, ensure nullable
             }
        });


        // Add the is_sync column using Schema builder, nullable and default null
        Schema::table('question_papers', function (Blueprint $table) {
            if (!Schema::hasColumn('question_papers', 'is_sync')) {
                $table->boolean('is_sync')->default(0)->after('file_path');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Drop the is_sync column first
        Schema::table('question_papers', function (Blueprint $table) {
            if (Schema::hasColumn('question_papers', 'is_sync')) {
                 $table->dropColumn('is_sync');
            }
        });

        // Change year column type back to integer using Schema Builder
        // Requires doctrine/dbal package
         Schema::table('question_papers', function (Blueprint $table) {
             if (Schema::hasColumn('question_papers', 'year')) {
                $table->integer('year')->nullable()->change(); // Change back to integer
             }
         });


        // Add topic_id column back using Schema builder
        Schema::table('question_papers', function (Blueprint $table) {
            // Add column only if it doesn't exist
            if (!Schema::hasColumn('question_papers', 'topic_id')) {
                // Assuming 'topics' table exists and has an 'id' column
                $table->foreignId('topic_id')->nullable()->after('exam_id');
                // Add constraint only if the column was successfully added and topics table exists
                if (Schema::hasTable('topics')) {
                    // Check if constraint already exists before adding
                    $foreignKeys = collect(DB::select("SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND COLUMN_NAME = ? AND REFERENCED_TABLE_NAME = 'topics'", [DB::getDatabaseName(), 'question_papers', 'topic_id']))->pluck('CONSTRAINT_NAME');
                    if ($foreignKeys->isEmpty()) {
                         $table->foreign('topic_id')->references('id')->on('topics');
                    }
                }
            }
        });
    }
}
