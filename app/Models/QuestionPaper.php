<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionPaper extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'year', 'exam_id', 'file_path', 'is_sync'];

    protected $casts = [
        'year' => 'date:Y', // Cast year to date, format to only year 'Y'
        'is_sync' => 'boolean', // Cast is_sync to boolean
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    // Removed topic() relationship as topic_id is removed

    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}
