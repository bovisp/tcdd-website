<?php

namespace App\Http\Controllers\Assessments\Assessment;

use Carbon\Carbon;
use App\Assessment;
use App\ContentBuilder;
use App\AssessmentAttempt;
use Illuminate\Support\Arr;
use App\Http\Controllers\Controller;

class AssessmentAttemptController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);

        $this->middleware(function ($request, $next) {
            preg_match_all("/\/assessment\/([\d]+)/",request()->url(),$matches);

            $assessment = Assessment::find((int) $matches[1][0]);

            $participantActive = $assessment->participants
                ->filter(function ($participant) {
                    return $participant->pivot->activated && $participant->id === auth()->id();
                })
                ->first();

            if (!$participantActive) {
                return redirect(env('APP_URL') . "/users/" . auth()->id());
            }

            $attempt = AssessmentAttempt::whereAssessmentParticipantId(
                $participantActive->pivot->id
            )->first();
            
            if ($attempt) {
                $time_remaining = $assessment->completion_time - Carbon::now()->diffInMinutes($attempt->created_at);

                if ($time_remaining <= 0) {
                    $participantActive->pivot->activated = 0;

                    $participantActive->pivot->save();
                    
                    return redirect(env('APP_URL') . "/users/{$participantActive->id}");
                }

                return redirect(env('APP_URL') . "/assessment/{$assessment->id}/attempt/{$attempt->id}");
            }

            return $next($request);
        })->only(['index']);

        $this->middleware(function ($request, $next) {
            preg_match_all("/\/assessment\/([\d]+)/",request()->url(),$matches);

            $assessment = Assessment::find((int) $matches[1][0]);

            $participantActive = $assessment->participants
                ->filter(function ($participant) {
                    return $participant->pivot->activated && $participant->pivot->participant_id === auth()->id();
                })
                ->first();

            if (!$participantActive) {
                return redirect(env('APP_URL') . "/users/" . auth()->id());
            }

            preg_match_all("/\/attempt\/([\d]+)/",request()->url(),$matches);

            $attempt = AssessmentAttempt::find((int) $matches[1][0]);

            if (!$attempt) {
                return redirect(env('APP_URL') . "/assessment/{$assessment->id}");    
            }

            $isValidAttemptForUser = $participantActive->pivot->id === $attempt->assessment_participant_id;

            if (!$isValidAttemptForUser) {
                return redirect(env('APP_URL') . "/assessment/{$assessment->id}");    
            }

            $time_remaining = $assessment->completion_time - Carbon::now()->diffInMinutes($attempt->created_at);

            if ($time_remaining <= 0) {
                $participantActive->pivot->activated = 0;

                $participantActive->pivot->save();

                return redirect(env('APP_URL') . "/users/{$participantActive->id}");
            }

            return $next($request);
        })->only(['show']);
    }

    public function index(Assessment $assessment)
    {
        $pages = $assessment->pages->map(function ($page) {
            return [
                'number' => $page->number,
                'items' => $page->assessmentPageContents->map(function($content) {
                    return $content->assessmentPageContentItems->map(function ($item) {
                        if ($item->type === 'Question') {
                            return [
                                'type' => $item->type,
                                'id' => $item->id,
                                'model_id' => $item->model_id,
                                'assessment_page_content_id' => $item->assessment_page_content_id,
                                'question_number' => $item->question_number,
                                'question_score' => $item->question_score
                            ];
                        }
                    });
                })
            ];
        })->map(function($page) {
            $questions = array_map(function ($question) {
                return [
                    'number' => $question['question_number'],
                    'score' => $question['question_score']
                ];
            }, array_filter(Arr::flatten($page['items'], 1)));

            return [
                'number' => $page['number'],
                'questions' => array_values(Arr::sort($questions, function ($value) {
                    return $value['number'];
                }))
            ];
        })->groupBy('number');

        return view('assessments.assessment.index', compact('assessment', 'pages'));
    }

    public function show(Assessment $assessment, AssessmentAttempt $attempt)
    {
        return view('assessments.assessment.show', compact('attempt', 'assessment'));
    }
}
