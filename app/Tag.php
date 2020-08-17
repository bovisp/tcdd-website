<?php

namespace App;

use App\Question;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Tag extends Model
{
    use HasTranslations;

    protected $translatable = [
        'name'
    ];

    protected $fillable = [
        'name'
    ];

    public function questions()
    {
        return $this->morphedByMany(Question::class, 'taggable');
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
