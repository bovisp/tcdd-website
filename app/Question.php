<?php

namespace App;

use App\User;
use App\Section;
use App\QuestionCategory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Question extends Model
{
    use HasTranslations;

    protected $translatable = [
        'name',
        'description'
    ];
    
    protected $fillable = [
        'name',
        'description',
        'score',
        'author_id',
        'question_category_id',
        'section_id'
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id', 'id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function questionCategory()
    {
        return $this->belongsTo(QuestionCategory::class);
    }

    public function toArray()
    {
        $attributes = parent::toArray();
        
        foreach ($this->getTranslatableAttributes() as $name) {
            $attributes[$name] = $this->getTranslation($name, app()->getLocale());
        }
        
        return $attributes;
    }
}
