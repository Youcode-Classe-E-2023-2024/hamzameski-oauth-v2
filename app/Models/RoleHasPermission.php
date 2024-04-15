<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleHasPermission extends Model
{
    use HasFactory;

    protected $table = 'role_has_permissions';
    protected $fillable = [
      'role_id',
      'permission_id'
    ];

    function role() {
        return $this->belongsTo(Role::class);
    }

    function permission() {
        return $this->belongsTo(Permission::class);
    }

}
