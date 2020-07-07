<?php

namespace App;

use App\PortalCategory;
use App\PortalLanguage;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class PortalCourse extends Model
{
    use HasTranslations;

    public $timestamps = false;

    protected $translatable = ['name'];

    protected $fillable = [
        'name',
        'moodle_course_id',
        'portal_category_id',
        'portal_language_id'
    ];

    public function portalLanguage()
    {
        return $this->belongsTo(PortalLanguage::class);
    }

    public function portalCategory()
    {
        return $this->belongsTo(PortalCategory::class);
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
