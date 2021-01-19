<?php

namespace App\Http\Controllers\Assessments\Assessments\Api;

use App\Assessment;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class AssessmentParticipantsActivationController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (auth()->user()->hasRole('administrator')) {
                return $next($request);
            }

            preg_match_all("/\/assessments\/([\d]+)/",request()->url(),$matches);

            $assessment = Assessment::find((int) $matches[1][0]);

            if ($assessment->editors->contains('id', auth()->id())) {
                return $next($request);
            }
            
            abort(403);
        });
    }
    
    public function update(Assessment $assessment)
    {
        $participant = DB::table('assessment_participants')
            ->where('id', (int) request()->query('id'))
            ->update([
                'activated' => (int) request()->query('activated') ? 0 : 1
            ]);
    }
}
