<?php

namespace App\Http\Controllers\Assessments\Assessment\Api;

use App\Assessment;
use App\AssessmentAttempt;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AttemptAnswersController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            preg_match_all("/\/assessment\/([\d]+)/",request()->url(),$matches);
    
            $assessment = Assessment::find((int) $matches[1][0]);
    
            preg_match_all("/\/attempt\/([\d]+)/",request()->url(),$matches);
    
            $attempt = AssessmentAttempt::find((int) $matches[1][0]);
    
            if ($attempt) {
                return $next($request);
            }
    
            return response()->json('You are not authorized to view this exam', 422);
        });
    }

    public function update(Assessment $assessment, AssessmentAttempt $attempt)
    {
        $answers = json_decode($attempt->answers, true);

        if (Arr::has($answers, 'question_' . request('answer')['id'] . '.' . request('answer')['key'])) {
            $answer = Arr::get($answers, 'question_' . request('answer')['id'] . '.' . request('answer')['key']);

            if (request('answer')['timestamp'] > $answer['timestamp']) {
                $answers['question_' . request('answer')['id']][request('answer')['key']] = [
                    'data' => request('answer')['data'],
                    'timestamp' => request('answer')['timestamp']
                ];
            }
        } else {
            $answers['question_' . request('answer')['id']] = [];

            $answers['question_' . request('answer')['id']][request('answer')['key']] = [
                'data' => request('answer')['data'],
                'timestamp' => request('answer')['timestamp']
            ];
        }

        $attempt->update([
            'answers' => json_encode($answers)
        ]);
    }
}
