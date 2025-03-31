<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserAnswersForQuestionpaperTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_answers_for_questionpaper', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('question_paper_id')->constrained()->onDelete('cascade'); // Changed from topic_id
            $table->foreignId('question_id')->constrained()->onDelete('cascade');
            $table->string('selected_option'); // Stores 'A', 'B', 'C', 'D'
            $table->timestamps();

            // Combined index for user_id and question_paper_id
            $table->index(['user_id', 'question_paper_id']); // Changed index
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_answers_for_questionpaper');
    }
}
