<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function exams()
    {
        return $this->belongsToMany(Exam::class, 'exam_topic_rel');
    }

    public function questionPapers()
    {
        return $this->hasMany(QuestionPaper::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}
