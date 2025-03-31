<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAnswerByTopic extends Model // Renamed class
{
    use HasFactory;

    protected $table = 'user_answers_by_topic'; // Set the correct table name

    protected $fillable = ['user_id', 'topic_id', 'question_id', 'selected_option'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
