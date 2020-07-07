<?php

namespace App\Http\Controllers\Admin\Portal\Courses\Api;

use App\PortalCourse;
use App\Http\Controllers\Controller;
use App\Http\Resources\Portal\PortalCourseResource;

class CoursesController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:administrator'])->except(['index']);
    }

    public function index()
    {
        return PortalCourseResource::collection(
            PortalCourse::all()
        );
    }

    public function store()
    {
        request()->validate([
            'name_en' => 'required|min:3',
            'name_fr' => 'required|min:3',
            'moodle_course_id' => 'integer|unique:portal_courses,moodle_course_id|exists:mysql2.mdl_course,id',
            'portal_language_id' => 'integer|exists:portal_languages,id',
            'portal_category_id' => 'integer|exists:portal_categories,id'
        ]);

        PortalCourse::create([
            'name' => [
                'en' => request('name_en'),
                'fr' => request('name_fr')
            ],
            'moodle_course_id' => request('moodle_course_id'),
            'portal_language_id' => request('portal_language_id'),
            'portal_category_id' => request('portal_category_id')
        ]);

        return response()->json([
            'data' => [
                'type' => 'success',
                'message' => 'Category successfully added.'
            ]
        ], 200);
    }
}
