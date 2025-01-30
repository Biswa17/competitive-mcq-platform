<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_text',
        'option_a',
        'option_b',
        'option_c',
        'option_d',
        'correct_option',
        'question_paper_id',
        'topic_id',
        'exam_id',
    ];

    /**
     * Relationship with QuestionPaper.
     */
    public function questionPaper()
    {
        return $this->belongsTo(QuestionPaper::class, 'question_paper_id');
    }

    /**
     * Relationship with Topic.
     */
    public function topic()
    {
        return $this->belongsTo(Topic::class, 'topic_id');
    }

    /**
     * Relationship with Exam.
     */
    public function exam()
    {
        return $this->belongsTo(Exam::class, 'exam_id');
    }
}
