<?php

namespace App;

use App\User;
use App\Section;
use App\Question;
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
        'section_id',
        'visible',
        'total_score'
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

    public function questions() {
        return $this->belongsToMany(Question::class, 'assessment_questions', 'assessment_id', 'question_id')
            ->using('App\Pivots\AssessmentQuestion')
            ->withPivot([
                'order',
                'page'
            ]);
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
