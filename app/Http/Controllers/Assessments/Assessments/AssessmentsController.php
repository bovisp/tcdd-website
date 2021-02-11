<?php

namespace App\Http\Controllers\Assessments\Assessments;

use App\Assessment;
use App\Http\Controllers\Controller;

class AssessmentsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['can:manage assessments']);
    }

    public function index()
    {
        return view('assessments.assessments.index');
    }
}
