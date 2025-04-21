<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateQuestionPapersTableForTopicYearSync extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Drop foreign key and topic_id column
        Schema::table('question_papers', function (Blueprint $table) {
            if (Schema::hasColumn('question_papers', 'topic_id')) {
                $table->dropForeign(['topic_id']);
                $table->dropColumn('topic_id');
            }
        });

        // Change year column to DATE using raw SQL
        if (Schema::hasColumn('question_papers', 'year')) {
            DB::statement("ALTER TABLE question_papers MODIFY year DATE NULL");
        }

        // Add is_sync column
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
        // Drop is_sync column
        Schema::table('question_papers', function (Blueprint $table) {
            if (Schema::hasColumn('question_papers', 'is_sync')) {
                $table->dropColumn('is_sync');
            }
        });

        // Change year column back to INTEGER using raw SQL
        if (Schema::hasColumn('question_papers', 'year')) {
            DB::statement("ALTER TABLE question_papers MODIFY year INT NULL");
        }

        // Re-add topic_id column with foreign key
        Schema::table('question_papers', function (Blueprint $table) {
            if (!Schema::hasColumn('question_papers', 'topic_id')) {
                $table->foreignId('topic_id')->nullable()->after('exam_id')->constrained('topics');
            }
        });
    }
}
