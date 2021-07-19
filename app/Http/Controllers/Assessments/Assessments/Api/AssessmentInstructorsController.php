<?php

namespace App\Http\Controllers\Assessments\Assessments\Api;

use App\User;
use App\Assessment;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class AssessmentInstructorsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['assessment-edit']);
    }

    public function destroy(Assessment $assessment)
    {
        DB::table('assessment_editors')
            ->where('assessment_id', $assessment->id)
            ->where('editor_id', request('instructor')['editor_id'])
            ->delete();

        return response()->json([
            'data' => [
                'type' => 'success',
                'message' => __('app_http_controllers_assessments_assessments_api_assessmentinstructors.destroy_message')
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
                'message' => __('app_http_controllers_assessments_assessments_api_assessmentinstructors.store_message'),
                'instructors' => $assessment->editors
            ]
        ], 200);
    }
}
