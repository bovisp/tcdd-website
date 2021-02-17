<?php

namespace App\Http\Controllers\Assessments\Assessment\Api;

use App\Question;
use App\Assessment;
use App\DrawingQuestion;
use App\AssessmentAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Events\AssessmentCompleted;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

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
        $answers = json_decode(request('answers'), true);

        $drawings = array_filter(
            array_map(function($question, $data) {
                if (array_search('drawing', array_keys($data)) !== false) {
                    return [
                        'data' => $data['drawing']['data'],
                        'question' => (int) explode('_', $question)[1]
                    ];
                }

                return false;
            }, array_keys($answers), $answers)
        );
        
        foreach ($drawings as $drawing) {
            $backgroundImage = unserialize(DrawingQuestion::find(
                Question::find($drawing['question'])->question_type_model_id
            )->drawing_options)['background_image'][0]['file'];

            $drawingDecode = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $drawing['data']));

            $drawingImagePath = "/public/assessments/" . $assessment->id . "/attempt/" . $attempt->id . "/question/" . $drawing['question'] . "/" . uniqid() . ".png";
           
            Storage::put($drawingImagePath, $drawingDecode);

            Image::make(public_path($backgroundImage))
                ->insert(storage_path("app" . $drawingImagePath))
                ->save(storage_path("app" . $drawingImagePath));

            $answers['question_' . $drawing['question']]['drawing']['data'] = $drawingImagePath;
        }

        $attempt->update([
            'answers' => $answers,
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
