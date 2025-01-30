<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->text('question_text');  // The actual question
            $table->string('option_a');  // Option A
            $table->string('option_b');  // Option B
            $table->string('option_c');  // Option C
            $table->string('option_d');  // Option D
            $table->string('correct_option');  // The correct answer (A, B, C, D)
            
            // Optional foreign keys for associating question with question paper or topic
            $table->foreignId('question_paper_id')->nullable()->constrained('question_papers')->onDelete('cascade');  // nullable as not every question has a question paper
            $table->foreignId('topic_id')->nullable()->constrained('topics')->onDelete('cascade');  // nullable as not every question has a topic
            
            // Foreign key for exam (could be linked indirectly via question paper or topic)
            $table->foreignId('exam_id')->nullable()->constrained('exams')->onDelete('cascade');  // Nullable, we will link exam via either topic or question paper
            
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
        Schema::dropIfExists('questions');
    }
}
