<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PersonalAccessToken;

//use Illuminate\Http\Request;

class ConfigController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('config');
    }

    public function facebook_access_token(Request $rq)
    {
        $at = $rq->facebook_access_token;
        $data = array('tokenable_type' => 'facebook_user_token', 'tokenable_id' => 2, 'name' => 'Facebook User Token', 'token' => $at, 'abilities' => 'Get facebook pages, groups, user information');
        PersonalAccessToken::updateOrCreate(array('tokenable_type' => 'facebook_user_token'), $data);
        return back()->with('msg', 'Cập nhật Access token thành công!');
    }
}
