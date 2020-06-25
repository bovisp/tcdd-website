<?php

namespace App\Http\Controllers\Supervisors\Api;

use App\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Classes\UpdateUserReporting;
use App\Http\Controllers\Controller;

class SupervisorsController extends Controller
{
    public function __construct ()
    {
        $this->middleware(['role:administrator']);
    }
    
    public function index($role)
    {
        return User::whereHas('roles', function ($query) use ($role) {
            return $query->where('name', $role);
        })->get();
    }

    public function store(User $user, $role)
    {
        $roleModel = Role::whereName($role)->first();

        (new UpdateUserReporting($user, $roleModel))->update();

        return response()->json([
            'data' => [
                'type' => 'success',
                'message' => 'Reporting structure successfully updated'
            ]
        ], 200);
    }
}
