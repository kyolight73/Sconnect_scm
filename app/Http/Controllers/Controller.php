<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Cookie;
use App\Models\PersonalAccessToken;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
        $this->facebook_access_token = PersonalAccessToken::where('tokenable_type', 'facebook_user_token')->first()->token ?? '';
    }

    public static function setCookie($name, $value, $minute)
    {
        Cookie::queue($name, $value, $minute);
    }

    public static function getCookie($name)
    {
        return Cookie::get($name);
    }
}
