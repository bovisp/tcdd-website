<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class AssessmentType extends Model
{
    use HasTranslations;

    public $timestamps = false;

    protected $translatable = ['name'];

    protected $fillable = [
        'name'
    ];

    public function toArray()
    {
        $attributes = parent::toArray();
        
        foreach ($this->getTranslatableAttributes() as $name) {
            $attributes[$name] = $this->getTranslation($name, app()->getLocale());
        }
        
        return $attributes;
    }
}
