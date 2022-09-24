<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AdminPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
    public function department(User $user)
    {
        return $user->checkPermissionAccess('cong-ty');
    }
    public function setting(User $user)
    {
        return $user->checkPermissionAccess('phong-banchuc-danh');
    }
    public function staff(User $user)
    {
        return $user->checkPermissionAccess('nhan-vien');
    }
    public function config(User $user)
    {
        return $user->checkPermissionAccess('phan-quyen');
    }
}
