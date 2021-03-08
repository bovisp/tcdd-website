<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class MultipleChoiceQuestionAnswer extends Model
{
    use HasTranslations;

    protected $translatable = [
        'text'
    ];
    
    protected $fillable = [
        'multiple_choice_question_id',
        'text',
        'is_correct'
    ];

    // protected $casts = [
    //     'is_correct' => 'boolean'
    // ];

    // public function getCorrectAttribute()
    // {
    //     return 'correct';
    // }

    public function toArray()
    {
        $attributes = parent::toArray();
        
        foreach ($this->getTranslatableAttributes() as $name) {
            $attributes[$name] = $this->getTranslation($name, app()->getLocale());
        }
        
        return $attributes;
    }
}
