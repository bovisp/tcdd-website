<?php

namespace App\Http\Controllers\Admin\Sections;

use App\Http\Controllers\Controller;

class SectionsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:administrator']);
    }

    public function index()
    {
        return view('admin.sections.index');
    }
}
