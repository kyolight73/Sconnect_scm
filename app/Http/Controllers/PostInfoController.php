<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PostInfo;

class PostInfoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        dd($id);
        return view('post_info.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('post_info.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $rq)
    {
        // $page_name = $input = preg_replace("/[^a-zA-Z]+/", "", $rq->page_name);
        // $page_id = $id;
        // $post_url = 'https://www.facebook.com/WoaDollsBeauty/posts/pfbid02eBGBtZ79QYTAFfSe3XWJuHSPExMDipXczFzAaTCWpEznhMKo2bKvjKR3xiW9CTibl';
        // $post_id = str_replace('https://www.facebook.com/' . $page_name . '/posts/', '', $post_url);

        // $at = $rq->access_token;
        // $curl = curl_init();

        // curl_setopt_array($curl, array(
        //     CURLOPT_URL => 'https://graph.facebook.com/v15.0/' . $page_id . '_' . $post_id . '?fields=reactions.type(ANGRY).limit(0).summary(total_count).as(reactions_angry),reactions.type(SAD).limit(0).summary(total_count).as(reactions_sad),reactions.type(LIKE).limit(0).summary(total_count).as(reactions_like),reactions.type(LOVE).limit(0).summary(total_count).as(reactions_love),reactions.type(WOW).limit(0).summary(total_count).as(reactions_wow),reactions.type(HAHA).limit(0).summary(total_count).as(reactions_haha),shares&access_token=' . $at,
        //     CURLOPT_RETURNTRANSFER => true,
        //     CURLOPT_ENCODING => '',
        //     CURLOPT_MAXREDIRS => 10,
        //     CURLOPT_TIMEOUT => 0,
        //     CURLOPT_FOLLOWLOCATION => true,
        //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //     CURLOPT_CUSTOMREQUEST => 'GET',
        // ));

        // $response = curl_exec($curl);

        // curl_close($curl);
        // $post_details = json_decode($response);
        // $post_id = $rq->post_id;
        // dd($post_id);
        // PostInfo::create($data);
        // return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        echo 'test code';
        // $post_info = PostInfo::where('id', $id)->delete();
        // return back()->with('msg', 'Xóa thành công');
    }

    public function delete($id)
    {
        // echo 'test code';
        $post_info = PostInfo::where('id', $id)->delete();
        return back()->with('msg', 'Xóa thành công');
    }
}
