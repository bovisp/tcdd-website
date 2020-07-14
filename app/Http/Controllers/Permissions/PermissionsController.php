<?php

namespace App\Http\Controllers\Permissions;

use App\Http\Controllers\Controller;

class PermissionsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:administrator']);
    }

    public function index()
    {
        return view('permissions.index');
    }
}
