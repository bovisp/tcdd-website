<?php

namespace App\Http\Controllers\Articles;

use App\Http\Controllers\Controller;

class ArticlesController extends Controller
{
    public function index()
    {
        return view('articles.index');
    }
}
