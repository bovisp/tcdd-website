<?php

namespace App;

use App\PortalCourse;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class PortalCategory extends Model
{
    use HasTranslations;

    public $timestamps = false;

    protected $translatable = ['name'];

    protected $fillable = [
        'name',
        'moodle_course_category_id',
        'moodle_parent_course_category_id'
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
