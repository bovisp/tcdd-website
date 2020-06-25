<?php

namespace App\Http\Controllers\Users;

use App\User;
use App\Http\Controllers\Controller;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:administrator'])->except(['show']);
        $this->middleware(['profile'])->only(['show']);
    }
    
    public function index()
    {
        return view('users.index');
    }

    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }
}
