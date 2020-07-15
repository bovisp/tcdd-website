<?php

namespace App\Http\Controllers\Assessments\Assessments\Api;

use App\User;
use App\Assessment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AssessmentParticipantsController extends Controller
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

        $assessment->participants()->sync(request('users'));

        return response()->json([
            'data' => [
                'type' => 'success',
                'message' => 'Participants updated'
            ]
        ], 200);
    }

    public function create(Assessment $assessment)
    {
        $users = User::all()
            ->filter(function ($user) use ($assessment) {
                return !$assessment->participants->contains('id', $user->id) && 
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

        $assessment->participants()->attach(request('users'));

        return response()->json([
            'data' => [
                'type' => 'success',
                'message' => 'Participants successfully added.'
            ]
        ], 200);
    }
}
