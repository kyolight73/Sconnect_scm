<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fanpage;

class FanpageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://graph.facebook.com/v15.0/me/accounts?access_token=EAAIv0uG4TMsBACQrKZC4N3ZCQbvv3V8K8QBL0ZBgHC6J6yJFmUR6Fe78oZAAE2O9ERfPLkViyTsJH6se4749z8u8z0PTZAZBtAZC2ep9y9KBoPEx3x9ZB6ZCzKoa7DqKoAvZBTDTwE41zxqJ7hAMOZAByyZBRPZA9NHI7I6G3CYpYWUeZCUgZDZD',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $all_pages = json_decode($response);

        $fanpage = Fanpage::get();

        return view('fanpage.index', compact('fanpage', 'all_pages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('fanpage.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        Fanpage::create($data);
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        echo $id;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Request $rq)
    {
        $edit_page = '';
        $fanpage = Fanpage::get();
        $fanpage_get = Fanpage::where('id', $id)->get();
        return view('fanpage.index', compact('edit_page', 'fanpage', 'fanpage_get'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $rq, $id)
    {
        $fanpage = Fanpage::where('id', $id)->first();
        $fanpage->theme = $rq->get('theme');
        $fanpage->page_name = $rq->get('page_name');
        $fanpage->link       = $rq->get('link');
        $fanpage->save();
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $fanpage = Fanpage::findOrFail($id);
        $fanpage->delete();
    }
}
