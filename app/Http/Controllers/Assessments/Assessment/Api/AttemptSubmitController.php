<?php

namespace App\Http\Controllers\Assessments\Assessment\Api;

use App\Question;
use App\Assessment;
use App\DrawingQuestion;
use App\AssessmentAttempt;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Events\AssessmentCompleted;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use App\Classes\Assessments\Assessment\MarkMultipleChoiceQuestion;

class AttemptSubmitController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            preg_match_all("/\/assessment\/([\d]+)/",request()->url(),$matches);
    
            $assessment = Assessment::find((int) $matches[1][0]);
    
            preg_match_all("/\/attempt\/([\d]+)/",request()->url(),$matches);
    
            $attempt = AssessmentAttempt::find((int) $matches[1][0]);
    
            if ($attempt) {
                if ($attempt->completed) {
                    return redirect(env('APP_URL') . "/users/" . auth()->id());
                }
                
                return $next($request);
            }
    
            return response()->json('You are not authorized to view this exam', 422);
        });
    }

    public function update(Assessment $assessment, AssessmentAttempt $attempt)
    {
        $allAnswers = [];

        $assessment->questions()->map(function ($question) use (&$allAnswers) {
            $item = $question['assessment_content']->assessmentPageContentItems[0];


            $allAnswers['question_' .$item->model_id] = [];

            switch ($question['model']->questionType->code) {
                case 'drawing':
                    $hasTextAnswer = DrawingQuestion::whereQuestionId($question['model']->id)->first()->text_answer;

                    if ($hasTextAnswer) {
                        $allAnswers['question_' .$item->model_id] = [
                            'drawing' => [
                                'data' => '',
                                'timestamp' => ''
                            ],
                            'text' => [
                                'data' => '',
                                'timestamp' => ''
                            ]
                        ];
                        
                        break;
                    } else {
                        $allAnswers['question_' .$item->model_id] = [
                            'drawing' => [
                                'data' => '',
                                'timestamp' => ''
                            ]
                        ];

                        break;
                    }
                case 'multiple_choice':
                    $allAnswers['question_' . $item->model_id] = [
                        'answers' => [
                            'data' => '',
                            'timestamp' => ''
                        ]
                    ];
                    
                    break;
                case 'essay':
                    $allAnswers['question_' . $item->model_id] = [
                        'text' => [
                            'data' => '',
                            'timestamp' => ''
                        ]
                    ];
                    
                    break;
            }
        });

        $participantAnswers = json_decode(request('answers'), true);

        foreach ($allAnswers as $q => $answer) {
            if (Arr::has($participantAnswers, $q)) {
                $question = Question::find((int) explode('_', $q)[1]);

                switch ($question->questionType->code) {
                    case 'drawing':
                        $hasTextAnswer = DrawingQuestion::whereQuestionId($question->id)->first()->text_answer;

                        if ($hasTextAnswer) {
                            $allAnswers['question_' . $question->id] = [
                                'drawing' => [
                                    'data' => $participantAnswers[$q]['drawing']['data']
                                ],
                                'text' => [
                                    'data' => $participantAnswers[$q]['text']['data']
                                ]
                            ];
                            
                            break;
                        } else {
                            $allAnswers['question_' .$question->id] = [
                                'drawing' => [
                                    'data' => $participantAnswers[$q]['drawing']['data']
                                ]
                            ];
    
                            break;
                        }
                    case 'multiple_choice':
                        if (Arr::has($participantAnswers, 'question_' . $question->id . '.answers')) {
                            $allAnswers['question_' . $question->id] = [
                                'answers' => [
                                    'data' => $participantAnswers[$q]['answers']['data']
                                ]
                            ];
                        }
                        
                        break;
                    case 'essay':
                        $allAnswers['question_' . $question->id] = [
                            'text' => [
                                'data' => $participantAnswers[$q]['text']['data']
                            ]
                        ];
                        
                        break;
                }
            }
        }

        $questionIds = [];

        foreach (array_keys($allAnswers) as $key) {
            $questionId = (int) explode('_', $key)[1];

            $question = Question::find($questionId);

            if ($question->questionType->code === 'multiple_choice') {
                (new MarkMultipleChoiceQuestion(
                    $question, $allAnswers[$key], $attempt
                ))->mark();
            }
        }

        $drawings = array_filter(
            array_map(function($question, $data) {
                if (array_search('drawing', array_keys($data)) !== false) {
                    return [
                        'data' => $data['drawing']['data'],
                        'question' => (int) explode('_', $question)[1]
                    ];
                }

                return false;
            }, array_keys($allAnswers), $allAnswers)
        );
        
        foreach ($drawings as $drawing) {
            $backgroundImage = unserialize(DrawingQuestion::find(
                Question::find($drawing['question'])->question_type_model_id
            )->drawing_options)['background_image'][0]['file'];

            $drawingImagePath = "/public/assessments/" . $assessment->id . "/attempt/" . $attempt->id . "/question/" . $drawing['question'] . "/" . uniqid() . ".png";

            if ($drawing['data'] !== '') {
                $drawingDecode = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $drawing['data']));

                Storage::put($drawingImagePath, $drawingDecode);

                Image::make(public_path($backgroundImage))
                    ->insert(storage_path("app" . $drawingImagePath))
                    ->save(storage_path("app" . $drawingImagePath));
            } else {
                Image::make(public_path($backgroundImage))
                    ->save(storage_path("app" . $drawingImagePath));
            }

            $allAnswers['question_' . $drawing['question']]['drawing']['data'] = $drawingImagePath;
        }

        $attempt->update([
            'answers' => $allAnswers,
            'completed' => 1
        ]);

        DB::table('assessment_participants')
            ->where('participant_id', auth()->id())
            ->where('assessment_id', $assessment->id)
            ->update(['activated' => 0]);

        event(new AssessmentCompleted($assessment->id, $attempt->id));

        return auth()->id();
    }
}
