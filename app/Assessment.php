<?php

namespace App;

use App\User;
use App\Section;
use App\Question;
use App\AssessmentPage;
use App\AssessmentType;
use App\AssessmentAttempt;
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
        'completion_time',
        'locked',
        'marking_completed',
        'marking_completed_on'
    ];

    protected $casts = [
        'marking_completed_on' => 'date'
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
        return $this->belongsToMany(User::class, 'assessment_participants', 'assessment_id', 'participant_id')
            ->withPivot('id', 'assessment_id', 'participant_id', 'activated');
    }

    public function pages()
    {
        return $this->hasMany(AssessmentPage::class)
            ->orderBy('number', 'asc');
    }

    public function attempts()
    {
        return $this->hasMany(AssessmentAttempt::class);
    }

    public function questions()
    {
        return $this->pages->map(function ($page) {
            return [
                'items' => $page->assessmentPageContents->map(function ($content) {
                    if ($content->assessmentPageContentItems[0]->type === 'Question') {
                        return [
                            'model' => Question::find($content->assessmentPageContentItems[0]->model_id),
                            'assessment_content' => $content,
                            'assessment_item' => $content->assessmentPageContentItems[0],
                            'assessment_id' => $this->id
                        ];
                    }
                })
            ];
        })
        ->flatten(2)
        ->filter();

        return $questions;
    }

    public function questionIds()
    {
        return $this->questions()->map(function ($question) {
            return $question['model']['id'];
        })->toArray();
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
