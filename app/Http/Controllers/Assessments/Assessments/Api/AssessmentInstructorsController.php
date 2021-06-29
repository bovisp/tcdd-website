<?php

namespace App\Http\Controllers\Assessments\Assessments\Api;

use App\User;
use App\Assessment;
use App\Http\Controllers\Controller;

class AssessmentInstructorsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['assessment-edit']);
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
                'message' => __('app_http_controllers_assessments_assessments_api_assessmentinstructors.update_message')
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
                !$assessment->participants->contains('id', $user->id) &&
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
                'message' => __('app_http_controllers_assessments_assessments_api_assessmentinstructors.store_message')
            ]
        ], 200);
    }
}
