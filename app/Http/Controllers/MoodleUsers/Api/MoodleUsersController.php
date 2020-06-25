<?php

namespace App\Http\Controllers\MoodleUsers\Api;

use App\User;
use App\Supervisor;
use App\Mail\UserRegistered;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class MoodleUsersController extends Controller
{
    public function __construct ()
    {
        $this->middleware(['role:administrator']);
    }

    public function create()
    {
        return DB::connection('mysql2')
            ->table('mdl_user')
            ->select('id', 'firstname', 'lastname', 'email')
            ->where('id', '>', 2)
            ->whereNotIn('id', User::pluck('moodle_id')->toArray())
            ->where('email', 'LIKE', '%@%.%')
            ->get();
    }

    public function store()
    {
        request()->validate([
            'section' => 'sometimes|nullable|integer|exists:sections,id',
            'role' => 'required|exists:roles,name',
            'users' => 'required|array',
            'users.*' => 'integer|unique:users,moodle_id',
        ]);

        foreach (request('users') as $user) {
            // Get the email address of the new user
            // from the Moodle database.
            $email = DB::connection('mysql2')
                ->table('mdl_user')
                ->select('email')
                ->where('id', $user)
                ->get()
                ->first()
                ->email;

            // Create the user
            $newUser = User::create([
                'moodle_id' => $user,
                'email' => $email,
                'section_id' => (int) request('section'),
                'password' => bcrypt($password = bin2hex(openssl_random_pseudo_bytes(5)))
            ]);

            // Attach the role to the user.
            $newUser->assignRole(
                Role::whereName(request('role'))->get()->first()
            );

            // For these roles, add them to the Supervisor table.
            if ($newUser->hasRole(['director', 'manager', 'employee'])) {
                Supervisor::create([ 
                    'user_id' => $newUser->id 
                ]);
            }

            Mail::to($newUser)->send(new UserRegistered($newUser, $password));
        }

        return response()->json([
            'data' => [
                'type' => 'success',
                'message' => 'Users successfully added'
            ]
        ], 200);
    }
}
