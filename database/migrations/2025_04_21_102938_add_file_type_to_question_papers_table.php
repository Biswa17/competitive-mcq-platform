<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFileTypeToQuestionPapersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('question_papers', function (Blueprint $table) {
            $table->string('file_type')->nullable(); // Add the new file_type column
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('question_papers', function (Blueprint $table) {
            $table->dropColumn('file_type'); // Remove the column on rollback
        });
    }
}
