<?php

namespace App\Http\Controllers\Admin\Portal\Categories;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function __construct()
    {

    }

    public function index()
    {
        return view('admin.portal.categories.index');
    }
}
