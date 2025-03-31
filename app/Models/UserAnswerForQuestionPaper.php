<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAnswerForQuestionPaper extends Model
{
    use HasFactory;

    protected $table = 'user_answers_for_questionpaper'; // Set the correct table name

    protected $fillable = [
        'user_id',
        'question_paper_id', // Changed from topic_id
        'question_id',
        'selected_option',
    ];

    // Define relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function questionPaper() // Changed relationship name
    {
        return $this->belongsTo(QuestionPaper::class); // Changed related model
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
