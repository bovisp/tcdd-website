<?php

namespace App\Http\Controllers\Questions\Categories;

use App\Http\Controllers\Controller;

class QuestionCategoriesController extends Controller
{
    public function __construct()
    {
        $this->middleware(['can:manage assessments'])->only(['index']);
    }

    public function index()
    {
        return view('questions.categories.index');
    }
}
