<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MarketingPolicy
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
    public function shortlink(User $user)
    {
        return $user->checkPermissionAccess('shortlink');
    }
    public function promotion(User $user)
    {
        return $user->checkPermissionAccess('quang-cao');
    }
}
