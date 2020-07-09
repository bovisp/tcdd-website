<?php

namespace App\Http\Controllers\Permissions\Api;

use App\User;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;

class UserPermissionsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:administrator']);
    }

    public function update(Permission $permission)
    {
        request()->validate([
            'users' => 'present|nullable|array',
            'users.*' => 'integer|exists:users,id',
        ]);

        $permission->users->each->revokePermissionTo($permission->name);

        foreach (request('users') as $userId) {
            $user = User::find($userId);

            $user->givePermissionTo($permission->name);
        }

        return response()->json([
            'data' => [
                'type' => 'success',
                'message' => 'Permissions updated'
            ]
        ], 200);
    }

    public function create(Permission $permission)
    {
        $users = User::with('permissions')
            ->get()
            ->filter(function ($user) use ($permission) {
                return $user->permissions->count() === 0 || $user->permissions->where('name', '!=', $permission->name)->count();
            })
            ->toArray();

        return response()->json(array_values($users), 200);
    }

    public function store(Permission $permission)
    {
        request()->validate([
            'users' => 'present|nullable|array',
            'users.*' => 'integer|exists:users,id'
        ]);

        foreach (request('users') as $userId) {
            $user = User::find($userId);

            $user->givePermissionTo($permission->name);
        }

        return response()->json([
            'data' => [
                'type' => 'success',
                'message' => 'Users successfully given this permission'
            ]
        ], 200);
    }
}
