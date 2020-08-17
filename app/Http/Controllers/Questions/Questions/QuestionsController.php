<?php

namespace App\Http\Controllers\Questions\Questions;

use App\Http\Controllers\Controller;

class QuestionsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['can:manage assessments']);
    }

    public function index()
    {
        return view('questions.questions.index');
    }
}
