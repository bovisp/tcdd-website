<?php

namespace App\Http\Controllers\Assessments\Assessments\Api;

use App\AssessmentPageContentItem;
use App\Http\Controllers\Controller;

class AssessmentQuestionContentController extends Controller
{
    public function changeScore(AssessmentPageContentItem $item)
    {
        $item->update([
            'question_score' => request('score')
        ]);

        return $item->question_score;
    }
}
