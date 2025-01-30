<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamTopicRel extends Model
{
    // Since this is a pivot table, you can define relationships
    protected $table = 'exam_topic_rel';

    // Define the relationship to exams
    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    // Define the relationship to topics
    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }
}
