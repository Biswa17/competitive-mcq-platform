<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('user_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('topic_id')->constrained()->onDelete('cascade');
            $table->foreignId('question_id')->constrained()->onDelete('cascade');
            $table->string('selected_option'); // Stores 'option_a', 'option_b', etc.
            $table->timestamps();

            // Combined index for user_id and topic_id
            $table->index(['user_id', 'topic_id']);
        });

        
    }

    public function down()
    {
        Schema::dropIfExists('user_answers');
    }
};
