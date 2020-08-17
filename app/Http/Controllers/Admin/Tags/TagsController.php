<?php

namespace App\Http\Controllers\Admin\Tags;

use App\Http\Controllers\Controller;

class TagsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:administrator']);
    }

    public function index()
    {
        return view('tags.index');
    }
}
