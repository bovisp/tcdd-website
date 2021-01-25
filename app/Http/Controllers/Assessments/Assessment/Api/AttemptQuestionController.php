<?php

namespace App\Http\Controllers\Assessments\Assessment\Api;

use App\Question;
use App\Assessment;
use App\AssessmentAttempt;
use App\Http\Controllers\Controller;

class AttemptQuestionController extends Controller
{
    public function show(Assessment $assessment, AssessmentAttempt $attempt, Question $question)
    {
        $resourceModel = 'App\\Http\\Resources\\Questions\\QuestionTypes\\' . ucfirst($question->questionType->code) . 'QuestionResource';

        return new $resourceModel($question);
    }
}
