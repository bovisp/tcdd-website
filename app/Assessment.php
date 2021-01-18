<?php

namespace App;

use App\User;
use App\Section;
use App\Question;
use App\AssessmentPage;
use App\AssessmentType;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Assessment extends Model
{
    use HasTranslations;

    protected $translatable = ['name', 'description'];

    protected $fillable = [
        'name',
        'description',
        'assessment_type_id',
        'section_id'
    ];

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function assessmentType()
    {
        return $this->belongsTo(AssessmentType::class);
    }

    public function editors()
    {
        return $this->belongsToMany(User::class, 'assessment_editors', 'assessment_id', 'editor_id');
    }

    public function participants()
    {
        return $this->belongsToMany(User::class, 'assessment_participants', 'assessment_id', 'participant_id');
    }

    public function pages()
    {
        return $this->hasMany(AssessmentPage::class)
            ->orderBy('number', 'asc');
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
