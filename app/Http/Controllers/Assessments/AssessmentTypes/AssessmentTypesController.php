<?php

namespace App\Http\Controllers\Assessments\AssessmentTypes;

use App\Http\Controllers\Controller;

class AssessmentTypesController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:administrator']);
    }

    public function index()
    {
        return view('assessments.assessment-types.index');
    }
}
