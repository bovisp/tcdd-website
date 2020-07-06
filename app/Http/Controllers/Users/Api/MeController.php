<?php

namespace App\Http\Controllers\Users\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Users\UserIndexResource;

class MeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function me()
    {
        return new UserIndexResource(auth()->user());
    }
}
