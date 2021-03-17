<?php

namespace App\Http\Controllers\Assessments\Assessments\Api;

use Carbon\Carbon;
use App\Assessment;
use App\AssessmentAttempt;
use App\Events\ShowAttemptResults;
use App\Http\Controllers\Controller;
use App\Events\PublishAssessmentAttempt;
use App\Http\Resources\Assessments\AssessmentAnswersResource;

class AssessmentReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware(['assessment-edit']);
    }

    public function update(Assessment $assessment, AssessmentAttempt $attempt)
    {
        $attempt->update([
            'show' => request('payload')['status'] ? 1 : 0
        ]);

        event(new ShowAttemptResults($attempt->participant()->id));

        if (request('payload')['status'] && !$attempt->published) {
            $attempt->update([
                'published' => 1,
                'publish_date' => Carbon::now()
            ]);

            event(new PublishAssessmentAttempt($attempt->participant()->id));
        }

        return AssessmentAnswersResource::collection(
            $assessment->attempts
        );
    }
}
