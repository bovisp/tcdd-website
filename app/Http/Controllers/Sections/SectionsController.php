<?php

namespace App\Http\Controllers\Sections;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SectionsController extends Controller
{
    public function index()
    {
        return view('sections.index');
    }
}
