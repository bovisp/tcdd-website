<?php

namespace App;

use App\PortalCourse;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class PortalLanguage extends Model
{
    use HasTranslations;

    public $timestamps = false;

    protected $translatable = ['language'];

    protected $fillable = [
        'language'
    ];

    public function portalCourses()
    {
        return $this->hasMany(PortalCourse::class);
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
