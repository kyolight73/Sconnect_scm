<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MediaPolicy
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
    public function view(User $user)
    {
        return $user->checkPermissionAccess('san-pham-goc');
    }
    public function platform(User $user)
    {
        return $user->checkPermissionAccess('nen-tang-chia-se-video');
    }
    public function topic(User $user)
    {
        return $user->checkPermissionAccess('chu-de');
    }
    public function channel_type(User $user)
    {
        return $user->checkPermissionAccess('loai-kenh');
    }
    public function channel(User $user)
    {
        return $user->checkPermissionAccess('kenh-video');
    }
    public function video(User $user)
    {
        return $user->checkPermissionAccess('video');
    }
}
