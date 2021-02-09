<?php
namespace App\Traits;

use App\Role;
use App\Permission;
trait HasRolesAndPermissions
{
    /**
     * @return mixed
     */

    public function hasRole(... $roles ) {
        foreach ($roles as $role) {
            if ($this->roles->contains('slug', $role)) {
                return true;
            }
        }
        return false;
    }

    public function deletePermissions(... $permissions )
    {
        $permissions = $this->getAllPermissions($permissions);
        $this->permissions()->detach($permissions);
        return $this;
    }

    /**
     * @param mixed ...$permissions
     * @return HasRolesAndPermissions
     */
    public function refreshPermissions(... $permissions )
    {
        $this->permissions()->detach();
        return $this->givePermissionsTo($permissions);
    }
 
    public function hasPermissionThroughRole($permission)
    {
        foreach ($permission->roles as $role){
            if($this->roles->contains($role)) {
                return true;
            }
        }
        return false;
    }
    public function roles()
    {
        return $this->belongsToMany(Role::class,'users_roles');
    }

    /**
     * @return mixed
     */


    public function permissions()
    {
        return $this->belongsToMany(Permission::class,'users_permissions');
    }

    protected function hasPermission($permission)
    {
        return (bool) $this->permissions->where('slug', $permission->slug)->count();
    }

    protected function hasPermissionTo($permission)
    {
        return $this->hasPermissionThroughRole($permission) || $this->hasPermission($permission);
    }

    protected function getAllPermissions(array $permissions)
    {
        return Permission::whereIn('slug',$permissions)->get();
    }

    public function givePermissionsTo(... $permissions)
    {
        $permissions = $this->getAllPermissions($permissions);
        if($permissions === null) {
            return $this;
        }
        $this->permissions()->saveMany($permissions);
        return $this;
    }

}