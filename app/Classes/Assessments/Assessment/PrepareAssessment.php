<?php

namespace App\Classes\Assessments\Assessment;

use App\Question;
use App\Assessment;
use App\DrawingQuestion;
use App\AssessmentAttempt;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use App\Classes\Assessments\Assessment\MarkMultipleChoiceQuestion;

class PrepareAssessment{
    protected $assessment;

    protected $attempt;

    protected $allAnswers = [];

    protected $participantAnswers = [];

    public function __construct(Assessment $assessment, AssessmentAttempt $attempt)
    {
        $this->assessment = $assessment;
        $this->attempt = $attempt;
    }

    public function prepare()
    {
        $this->allAnswers = $this->constructCompleteAnswerArray();

        $this->participantAnswers = $this->populateCompleteAnswerArray();

        $this->markMultipleChoice();

        $drawings = $this->prepareDrawingQuestionImages();

        $this->saveDrawingImages($drawings);

        return $this->allAnswers;
    }

    protected function saveDrawingImages($drawings)
    {
        foreach ($drawings as $drawing) {
            $backgroundImage = unserialize(DrawingQuestion::find(
                Question::find($drawing['question'])->question_type_model_id
            )->drawing_options)['background_image'][0]['file'];

            $drawingImagePath = "/public/assessments/" . $this->assessment->id . "/attempt/" . $this->attempt->id . "/question/" . $drawing['question'] . "/" . uniqid() . ".png";
            
            if ($drawing['data']) {
                $drawingDecode = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $drawing['data']));

                Storage::put($drawingImagePath, $drawingDecode);

                Image::make(public_path($backgroundImage))
                    ->insert(storage_path("app" . $drawingImagePath))
                    ->save(storage_path("app" . $drawingImagePath));
            } else {
                Storage::copy(str_replace('storage', 'public', $backgroundImage), $drawingImagePath);
            }

            $this->allAnswers['question_' . $drawing['question']]['drawing']['data'] = $drawingImagePath;
        }
    }

    protected function prepareDrawingQuestionImages()
    {
        return array_filter(
            array_map(function($question, $data) {
                if (array_search('drawing', array_keys($data)) !== false) {
                    return [
                        'data' => $data['drawing']['data'],
                        'question' => (int) explode('_', $question)[1]
                    ];
                }

                return false;
            }, array_keys($this->allAnswers), $this->allAnswers)
        );
    }

    protected function markMultipleChoice()
    {
        $questionIds = [];

        foreach (array_keys($this->allAnswers) as $key) {
            $questionId = (int) explode('_', $key)[1];

            $question = Question::find($questionId);

            if ($question->questionType->code === 'multiple_choice') {
                (new MarkMultipleChoiceQuestion(
                    $question, $this->allAnswers[$key], $this->attempt
                ))->mark();
            }
        }
    }

    protected function populateCompleteAnswerArray()
    {
        $participantAnswers = json_decode(request('answers'), true);

        foreach ($this->allAnswers as $q => $answer) {
            if (Arr::has($participantAnswers, $q)) {
                $question = Question::find((int) explode('_', $q)[1]);

                switch ($question->questionType->code) {
                    case 'drawing':
                        $hasTextAnswer = DrawingQuestion::whereQuestionId($question->id)->first()->text_answer;

                        if ($hasTextAnswer) {
                            $this->allAnswers['question_' . $question->id] = [
                                'drawing' => [
                                    'data' => Arr::has($participantAnswers, $q . '.drawing.data')  ? $participantAnswers[$q]['drawing']['data'] : ''
                                ],
                                'text' => [
                                    'data' => Arr::has($participantAnswers, $q . '.text.data') ? $participantAnswers[$q]['text']['data'] : ''
                                ]
                            ];
                            
                            break;
                        } else {
                            $this->allAnswers['question_' .$question->id] = [
                                'drawing' => [
                                    'data' => $participantAnswers[$q]['drawing']['data']
                                ]
                            ];
    
                            break;
                        }
                    case 'multiple_choice':
                        if (Arr::has($participantAnswers, 'question_' . $question->id . '.answers')) {
                            $this->allAnswers['question_' . $question->id] = [
                                'answers' => [
                                    'data' => $participantAnswers[$q]['answers']['data']
                                ]
                            ];
                        }
                        
                        break;
                    case 'essay':
                        $this->allAnswers['question_' . $question->id] = [
                            'text' => [
                                'data' => $participantAnswers[$q]['text']['data']
                            ]
                        ];
                        
                        break;
                }
            }
        }
    }

    protected function constructCompleteAnswerArray()
    {
        $allAnswers = [];

        $this->assessment->questions()->map(function ($question) use (&$allAnswers) {
            $item = $question['assessment_content']->assessmentPageContentItems[0];


            $allAnswers['question_' .$item->model_id] = [];

            switch ($question['model']->questionType->code) {
                case 'drawing':
                    $hasTextAnswer = DrawingQuestion::whereQuestionId($question['model']->id)->first()->text_answer;

                    if ($hasTextAnswer) {
                        $allAnswers['question_' .$item->model_id] = [
                            'drawing' => [
                                'data' => ''
                            ],
                            'text' => [
                                'data' => ''
                            ]
                        ];
                        
                        break;
                    } else {
                        $allAnswers['question_' .$item->model_id] = [
                            'drawing' => [
                                'data' => ''
                            ]
                        ];

                        break;
                    }
                case 'multiple_choice':
                    $allAnswers['question_' . $item->model_id] = [
                        'answers' => [
                            'data' => ''
                        ]
                    ];
                    
                    break;
                case 'essay':
                    $allAnswers['question_' . $item->model_id] = [
                        'text' => [
                            'data' => ''
                        ]
                    ];
                    
                    break;
            }
        });

        return $allAnswers;
    }
}