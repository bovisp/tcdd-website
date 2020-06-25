<?php

namespace App\Http\Controllers\Roles\Api;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class RolesController extends Controller
{
    public function index()
    {
        return DB::connection('mysql')
            ->table('roles')
            ->select('id', 'name')
            ->get();
    }
}
