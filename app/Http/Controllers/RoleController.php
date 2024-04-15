<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use App\Models\RoleHasPermission;
use App\Models\UserHasRole;
use http\Env\Response;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /***************************** CRUD of user_has_roles *****************************/
    public function giveRoleToUser(Request $request, $roleId, $userId)
    {
        $adminRoleId = Role::where('name', 'admin')->value('id');
        if($adminRoleId == $roleId) {
            return response()->json(['error' => 'Forbidden role assignment'], 403);
        }

        $roleId = Role::where('id', $roleId)->pluck('id');
        UserHasRole::create([
            'user_id' => $userId,
            'role_id' => $roleId[0]
        ]);

        return response()->json(['message' => 'Role assigned to user successfully'], 200);
    }

    public function updateRoleOfUser(Request $request, $id) {
        $userId = $request->input('user_id');
        $roleId = $request->input('role_id');

        $row = UserHasRole::where('id', $id)->first();
        $row->update([
            'user_id' => $userId,
            'role_id' => $roleId,
        ]);

        return response()->json('user role updated successfully');
    }

    public function deleteRoleFromUser(Request $request, $role_id, $user_id) {
        $row = UserHasRole::where('role_id', $role_id)
            ->where('user_id', $user_id)
            ->first();

        if($row) {
            $row->delete();
            return response()->json('user role deleted successfully');
        }

        return response()->json('row can not be found');
    }







    /***************************** CRUD of role_has_permissions *****************************/
    public function givePermissionToRole(Request $request)
    {
        $roleId = $request->input('role_id');
        $permissionId = $request->input('permission_id');

        RoleHasPermission::create([
            'role_id' => $roleId,
            'permission_id' => $permissionId
        ]);

        return response()->json('permission assigned to the role successfully');
    }

    public function updatePermissionOfRole(Request $request, $id) {
        $roleId = $request->input('role_id');
        $permissionId = $request->input('permission_id');

        $row = RoleHasPermission::where('id', $id)->first();
        $row->update([
            'role_id' => $roleId,
            'permission_id' => $permissionId,
        ]);

        return response()->json('role permission updated successfully');
    }

    public function deletePermissionFromRole(Request $request, $id) {
        $row = RoleHasPermission::where('id', $id)->first();

        $row->delete();
        return response()->json('role permission deleted successfully');
    }






    /***************************** CRUD of permissions *****************************/
    function storePermission(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3|unique:permissions'
        ]);

        Permission::create([
            'name' => $request->name
        ]);

        return response()->json([
            'status' => 'success'
        ]);
    }

    public function updatePermission(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|min:3|unique:permissions'
        ]);

        $permission = Permission::findOrFail($id);
        $permission->update([
            'name' => $request->name
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Permission updated successfully'
        ]);
    }

    public function deletePermission($id)
    {
        $permission = Permission::findOrFail($id);
        $permission->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Permission deleted successfully'
        ]);
    }








    /***************************** CRUD of roles *****************************/
    function storeRole(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3|unique:roles'
        ]);

        Role::create([
            'name' => $request->name
        ]);

        return response()->json([
            'status' => 'success'
        ]);
    }

    public function updateRole(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|min:3|unique:roles'
        ]);

        $role = Role::findOrFail($id);
        $role->update([
            'name' => $request->name
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Role updated successfully'
        ]);
    }

    public function deleteRole($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Role deleted successfully'
        ]);
    }









    /****************************** static methods *****************************/
    static function giveRoleToUser_static($userId, $roleName)
    {
        $roleId = Role::where('name', $roleName)->pluck('id');
        UserHasRole::create([
            'user_id' => $userId,
            'role_id' => $roleId[0]
        ]);
    }

    static function givePermissionToRole_static($roleId, $permissionId)
    {
        RoleHasPermission::create([
            'role_id' => $roleId,
            'permission_id' => $permissionId
        ]);

        return response()->json(['status', 'success']);
    }

    static function userCan($user_id, $permissions)
    {
        $userRole = UserHasRole::where('user_id', $user_id)->first();

        if (!$userRole) {
            return response()->json([
                'error' => 'User role not found'
            ], 404);
        }

        $rolePermissions = RoleHasPermission::where('role_id', $userRole->role_id)->pluck('permission');

        if ($rolePermissions->isEmpty()) {
            return response()->json([
                'error' => 'Role permissions not found'
            ], 404);
        }

        $permissionsIds = Permission::whereIn('name', $permissions)->pluck('id')->toArray();

        foreach ($permissionsIds as $permission) {
            if (!$rolePermissions->contains($permission)) {
                return false;
            }
        }

        return true;
    }


    static function getRolesNames()
    {
        $roles = Role::all();

        return response()->json($roles);
    }
}
