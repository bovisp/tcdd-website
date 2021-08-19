<?php

namespace App\Http\Controllers\Permissions\Api;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use App\Http\Resources\Permissions\PermissionResource;

class PermissionsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:administrator']);
    }

    public function index()
    {
        return PermissionResource::collection(
            Permission::all()
        );
    }

    public function store()
    {
        request()->validate([
            'name' => 'required|min:3|unique:permissions,name'
        ]);

        Permission::create([
            'name' => strtolower(request('name'))
        ]);

        return response()->json([
            'data' => [
                'type' => 'success',
                'message' => __('app_http_controllers_permissions_api_permissions.store_message')
            ]
        ], 200);
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();

        return response()->json([
            'data' => [
                'type' => 'success',
                'message' => __('app_http_controllers_permissions_api_permissions.destroy_message')
            ]
        ], 200);
    }
}
