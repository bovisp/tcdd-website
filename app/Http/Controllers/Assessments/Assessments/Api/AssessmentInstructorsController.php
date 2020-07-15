<?php

namespace App\Http\Controllers\Assessments\Assessments\Api;

use App\User;
use App\Assessment;
use App\Http\Controllers\Controller;

class AssessmentInstructorsController extends Controller
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
        request()->validate([
            'users' => 'present|nullable|array',
            'users.*' => 'integer|exists:users,id',
        ]);

        $assessment->editors()->sync(request('users'));

        return response()->json([
            'data' => [
                'type' => 'success',
                'message' => 'Instructors updated'
            ]
        ], 200);
    }

    public function create(Assessment $assessment)
    {
        $users = User::with('permissions')
            ->get()
            ->filter(function ($user) use ($assessment) {
                return $user->can('manage assessments') && 
                !$assessment->editors->contains('id', $user->id) && 
                !$user->hasRole('administrator');
            })
            ->toArray();

        return response()->json(array_values($users), 200);
    }

    public function store(Assessment $assessment)
    {
        request()->validate([
            'users' => 'present|nullable|array',
            'users.*' => 'integer|exists:users,id'
        ]);

        $assessment->editors()->attach(request('users'));

        return response()->json([
            'data' => [
                'type' => 'success',
                'message' => 'Instructors successfully given permission to edit this exam.'
            ]
        ], 200);
    }
}
