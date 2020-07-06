<?php

namespace App\Http\Controllers\Admin\Portal\Languages;

use App\Http\Controllers\Controller;

class LanguagesController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:administrator']);
    }

    public function index()
    {
        return view('admin.portal.languages.index');
    }
}
