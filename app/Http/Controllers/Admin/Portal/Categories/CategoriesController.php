<?php

namespace App\Http\Controllers\Admin\Portal\Categories;

use App\Http\Controllers\Controller;

class CategoriesController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:administrator']);
    }

    public function index()
    {
        return view('admin.portal.categories.index');
    }
}
