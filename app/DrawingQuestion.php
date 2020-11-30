<?php

namespace App;

use App\Question;
use Illuminate\Database\Eloquent\Model;

class DrawingQuestion extends Model
{
    protected $fillable = [
        'question_id',
        'rich_text',
        'text_answer',
        'drawing_options'
    ];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
