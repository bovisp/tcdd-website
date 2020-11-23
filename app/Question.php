<?php

namespace App;

use App\Tag;
use App\User;
use App\Section;
use App\QuestionType;
use App\ContentBuilder;
use App\QuestionCategory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Question extends Model
{
    use HasTranslations;

    protected $translatable = [
        'name',
        'marking_guide'
    ];
    
    protected $fillable = [
        'name',  
        'score',
        'author_id',
        'question_category_id',
        'section_id',
        'owner_id',
        'marking_guide',
        'question_type_model_id',
        'question_type_id',
    ];

    public function contentBuilder()
    {
        return $this->morphMany(ContentBuilder::class, 'contentable');
    }

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

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function editors()
    {
        return $this->belongsToMany(User::class, 'question_editors', 'question_id', 'user_id');
    }

    public function questionType()
    {
        return $this->belongsTo(QuestionType::class);
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
