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

    // Removed questionPapers() relationship as topic_id is removed from question_papers table

    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}
