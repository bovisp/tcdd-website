<?php

namespace App\Http\Controllers\Admin\Portal\Courses;

use App\Http\Controllers\Controller;

class CoursesController extends Controller
{
    public function index()
    {
        return view('admin.portal.courses.index');
    }
}
