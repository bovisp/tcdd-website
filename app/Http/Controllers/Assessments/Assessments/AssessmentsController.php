<?php

namespace App\Http\Controllers\Assessments\Assessments;

use App\Assessment;
use App\Http\Controllers\Controller;

class AssessmentsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['can:manage assessments'])->only(['index']);

        $this->middleware(function ($request, $next) {
            if (auth()->user()->hasRole('administrator')) {
                return $next($request);
            }

            preg_match_all("/\/assessments\/([\d]+)/",request()->url(),$matches);

            $assessment = Assessment::find((int) $matches[1][0]);

            if ($assessment->editors->contains('id', auth()->id())) {
                return $next($request);
            }

            if ($assessment->participants->contains('id', auth()->id())) {
                return $next($request);
            }
            
            abort(403);
        })->only(['show']);
    }

    public function index()
    {
        return view('assessments.assessments.index');
    }

    public function show(Assessment $assessment)
    {
        return view('assessments.assessments.show', compact('assessment'));
    }
}
