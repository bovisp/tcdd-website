<?php

namespace App;

use App\Question;
use Illuminate\Database\Eloquent\Model;

class EssayQuestion extends Model
{
    protected $fillable = [
        'question_id',
        'rich_text'
    ];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
