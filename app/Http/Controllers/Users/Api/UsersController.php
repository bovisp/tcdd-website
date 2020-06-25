<?php

namespace App\Http\Controllers\Users\Api;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Users\UserShowResource;
use App\Http\Resources\Users\UserIndexResource;

class UsersController extends Controller
{
    public function __construct ()
    {
        $this->middleware(['role:administrator'])->except(['show']);
    }

    public function index()
    {
        return UserIndexResource::collection(
            User::all()
        );
    }

    public function show(User $user)
    {
        return new UserShowResource(User::find($user->id));
    }
}
