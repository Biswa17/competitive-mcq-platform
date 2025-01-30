<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // Fillable attributes
    protected $fillable = [
        'name', 
        'description', 
        'parent_id',
        'is_popular'
    ];

    /**
     * Relationship to get the subcategories (children) of a category.
     */
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id')->with('children');
    }

    /**
     * Relationship to get the parent category.
     */
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function exams()
    {
        return $this->belongsToMany(Exam::class, 'category_exam_rel', 'category_id', 'exam_id');
    }

}
