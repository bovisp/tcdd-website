<?php

namespace App\Http\Controllers\Issues;

use App\Http\Controllers\Controller;

class IssuesController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }
    
    public function index()
    {
        return view('issues.index');
    }
}
