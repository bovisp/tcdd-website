<?php

namespace App\Http\Controllers\Questions\Types;

use App\Http\Controllers\Controller;

class QuestionTypesController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:administrator']);
    }

    public function index()
    {
        return view('questions.types.index');
    }
}
