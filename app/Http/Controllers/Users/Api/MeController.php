<?php

namespace App\Http\Controllers\Users\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Users\UserMeResource;

class MeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function me()
    {
        return new UserMeResource(auth()->user());
    }
}
