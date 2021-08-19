<?php

namespace App\Http\Controllers\Users\Api;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PasswordChangeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
        
        $this->middleware(['profile']);
    }

    public function update(User $user)
    {
        request()->validate([
            'password' => 'required|min:8',
            'passwordConfirm' => 'required|min:8|same:password'
        ]);

        $user->update([
            'password' => bcrypt(request('password'))
        ]);

        return response()->json([
            'data' => [
                'type' => 'success',
                'message' => 'Password successfully changed.'
            ]
        ], 200);
    }
}
