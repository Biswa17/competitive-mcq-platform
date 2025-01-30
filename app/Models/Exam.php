<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    // The table associated with the model
    protected $table = 'exams';

    // The attributes that are mass assignable
    protected $fillable = [
        'name', 
        'description', 
        'is_active',
    ];

    // The attributes that should be cast to native types
    protected $casts = [
        'is_active' => 'boolean',
    ];

    // The timestamps for created_at and updated_at
    public $timestamps = true;

    /**
     * Get the exams that are active.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_exam_rel', 'exam_id', 'category_id');
    }

    public function topics()
    {
        return $this->belongsToMany(Topic::class, 'exam_topic_rel')->distinct();
    }


    public function questionPapers()
    {
        return $this->hasMany(QuestionPaper::class);
    }

}
