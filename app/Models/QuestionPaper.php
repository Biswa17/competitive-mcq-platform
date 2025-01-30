<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionPaper extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'exam_id'];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }


    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}
