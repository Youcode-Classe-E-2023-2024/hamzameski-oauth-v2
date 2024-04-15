<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    function index()
    {
        $users = User::all();
        return response()->json($users);
    }


    function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request;
        $user = [
            "name" => $data['name'],
            "email" => $data['email'],
            "password" => Hash::make($data["password"])
        ];

        $user = User::create($user);
        RoleController::assignRole($user->id, 'user');
        $status = "success";
        $response = ['user' => $user,
            'status' => $status];
        return response()->json($response);
    }


    function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'string',
            'password' => 'string|min:8'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $user = User::find($request->id);

        if (isset($request->name)) {
            $user->name = $request->name;
        }
        if (isset($request->password)) {
            $user->password = $request->password;
        }

        $user->save();
        return response()->json([
            'status' => 'success'
        ]);
    }


    function destroy(Request $request)
    {
        $user = User::find($request->id);
        $user->delete();
        return response()->json([
            'status' => 'success'
        ]);
    }

    public function getUserDetails($id)
    {
        $user = User::where('id', $id)->get()->first();
        return response()->json(['user' => $user], 200);
    }

    public function getUserRolesNames($id) {
        $user = User::find($id);
        if ($user) {
            $roles = $user->roles()->select('roles.id as role_id', 'roles.name')->get();
            return response()->json($roles);
        }
        return response()->json(['failed'=> 'user id does not exist']);
    }



}
