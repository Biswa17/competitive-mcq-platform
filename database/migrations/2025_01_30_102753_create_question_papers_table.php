<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionPapersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('question_papers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('year');
            $table->foreignId('exam_id')->constrained('exams');  // Assuming you have an 'exams' table
            $table->foreignId('topic_id')->nullable()->constrained('topics');  // topic_id is now nullable
            $table->string('file_path')->nullable();  // Optional field to store file path (if any)
            $table->timestamps();  // created_at, updated_at timestamps
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('question_papers');
    }
}
