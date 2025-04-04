<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuestionImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id',
        'image_path',
    ];

    /**
     * Get the question that owns the image.
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
}
