<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fanpage;
use App\Models\PostInfo;
use App\Models\FanpageRecord;
use Illuminate\Support\Facades\DB;
use App\Utils;
use Exception;

class FanpageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $rq)
    {
        $name = $rq->name;
        $filter = $rq->filter;
        switch ($filter) {
            case 'like_desc':
                $filter_att = 'likes_count';
                $order_type = 'DESC';
                break;
            case 'like_asc':
                $filter_att = 'likes_count';
                $order_type = 'ASC';
                break;
            default:
                $filter_att = 'likes_count';
                $order_type = 'DESC';
                break;
        }
        $fanpage = Fanpage::where('page_name', 'like', "%$name%")->orWhere('page_id', $name)->orderBy($filter_att, $order_type)->paginate(10);
        $update_at = Fanpage::select('updated_at')->orderBy('likes_count', 'DESC')->first();
        return view('fanpage.index', compact('fanpage', 'update_at', 'filter'));
    }

    public function get_api_facebook_pages()
    {
        $curl = curl_init();
        $at = $this->facebook_access_token;
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://graph.facebook.com/v15.0/me/accounts?access_token=' . $at,
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
        if (empty($all_pages->data)) {
            return back()->with('msg', 'Xảy ra lỗi trong quá trình gọi API, hãy kiểm tra lại Access Token!');
        }
        foreach ($all_pages->data as $add) {

            $curl = curl_init();
            $page_at = $add->access_token;
            curl_setopt_array($curl, [
                CURLOPT_URL => 'https://graph.facebook.com/v15.0/me?fields=id,name,fan_count,followers_count,link,picture&access_token=' . $page_at . '',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
            ]);
            $response = curl_exec($curl);
            $page_info = json_decode($response);

            curl_close($curl);
            $array = array('page_id' => $add->id, 'page_theme' => $add->category, 'page_name' => $add->name, 'picture' => $page_info->picture->data->url, 'page_url' => $page_info->link, 'likes_count' => $page_info->fan_count, 'access_token' => $add->access_token);
            $fanpage = Fanpage::updateOrCreate(array('page_id' => $add->id), $array);
            $update_at = date('Y-m-d H:i:s');
            // Lưu data vào record
            $record_date = date('Y-m-d');
            $record = array('record_date' => $record_date, 'page_id' => $add->id, 'likes_count' => $page_info->fan_count, 'name' => $add->name);
            $check_exist = FanpageRecord::where('page_id', $add->id)->where('record_date', $record_date)->count();
            if ($check_exist == 0) {
                $fanpage_record = FanpageRecord::create($record);
            }
            $msg = '';
            // else {
            //     dd('Record đã tồn tại');
            // }
        }
        return redirect('/fanpage')->with('msg', 'Lấy dữ liệu thành công');
    }

    public function add_post_api_from_to($id, Request $rq)
    {
        $page_id = $id;
        $at = Fanpage::select('access_token')->where('page_id', $page_id)->first()->access_token;
        // dd($at);
        $from = $rq->from;
        $to = $rq->to;
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://graph.facebook.com/v15.0/' . $page_id . '?fields=feed.since(' . $from . ').until(' . $to . ')&access_token=' . $at,
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
        $post_list = json_decode($response);
        // dd($post_list);
        if (isset($post_list->error) || is_null($post_list)) {
            return redirect()->back()->with('msg', 'Xảy ra lỗi khi gọi bài viết!');
        }
        // $page_two = json_decode(file_get_contents($post_list->feed->paging->next));
        // dd($post_list);
        foreach ($post_list->feed->data as $add) {
            $page_post_id = $add->id;
            $page_id_with_line = $page_id . '_';
            $post_id = str_replace($page_id_with_line, '', $page_post_id);
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => 'https://graph.facebook.com/v15.0/' . $page_post_id . '?fields=reactions.type(ANGRY).limit(0).summary(total_count).as(reactions_angry),reactions.type(SAD).limit(0).summary(total_count).as(reactions_sad),reactions.type(LIKE).limit(0).summary(total_count).as(reactions_like),reactions.type(LOVE).limit(0).summary(total_count).as(reactions_love),reactions.type(WOW).limit(0).summary(total_count).as(reactions_wow),reactions.type(HAHA).limit(0).summary(total_count).as(reactions_haha),shares,permalink_url,message,created_time&access_token=' . $at,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
            ]);
            $response = curl_exec($curl);
            $post_info = json_decode($response);

            curl_close($curl);
            $reactions_like = $post_info->reactions_like->summary->total_count;
            $reactions_sad = $post_info->reactions_sad->summary->total_count;
            $reactions_angry = $post_info->reactions_angry->summary->total_count;
            $reactions_love = $post_info->reactions_love->summary->total_count;
            $reactions_wow = $post_info->reactions_wow->summary->total_count;
            $reactions_haha = $post_info->reactions_haha->summary->total_count;
            $share = $post_info->shares->count ?? '0';
            $link = $post_info->permalink_url;
            $message = $post_info->message;
            $created_time_raw = date_create($post_info->created_time);
            $created_time = date_format($created_time_raw, "Y-m-d H:i:s");
            // $share = 20;
            $reactions_count = $reactions_like + $reactions_sad + $reactions_angry + $reactions_love + $reactions_wow + $reactions_haha;
            $data = array('page_id' => $page_id, 'post_id' => $post_id, 'message' => $message, 'post_create' => $created_time, 'link' => $link, 'like_count' => $reactions_like, 'angry_count' => $reactions_angry, 'sad_count' => $reactions_sad, 'love_count' => $reactions_love, 'wow_count' => $reactions_wow, 'haha_count' => $reactions_haha, 'shares_count' => $share);
            // dd($data);
            PostInfo::updateOrCreate(array('post_id' => $post_id), $data);
            // $update_at = date('Y-m-d H:i:s');
        }
        return back()->with('msg', 'Thêm bài viết mới thành công!');
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
    public function show($id, Request $rq)
    {

        // dd($post_details->reactions_like->summary->total_count);
        // return redirect(route('post_info.index'));
    }

    public function show_post_info($id, Request $rq)
    {
        // $page_name = $input = preg_replace("/[^a-zA-Z]+/", "", $rq->page_name);
        // $post_id = str_replace('https://www.facebook.com/' . $page_name . '/posts/', '', $post_url);
        $search_from = strtotime($rq->search_from);
        $day_from = date('d', $search_from);
        $month_from = date('m', $search_from);
        $search_to = strtotime($rq->search_to);
        $day_to = date('d', $search_to);
        $month_to = date('m', $search_to);
        $page_id = $id;
        $at = $rq->access_token;
        $name = $rq->name;
        $filter = $rq->filter;
        switch ($filter) {
            case 'date_desc':
                $filter_att = 'post_create';
                $order_type = 'DESC';
                break;
            case 'date_asc':
                $filter_att = 'post_create';
                $order_type = 'ASC';
                break;
            case 'interact_desc':
                $filter_att = 'like_count';
                $order_type = 'DESC';
                break;
            case 'interact_asc':
                $filter_att = 'like_count';
                $order_type = 'ASC';
                break;
            default:
                $filter_att = 'post_create';
                $order_type = 'DESC';
                break;
        }
        if (isset($rq->search_from) || isset($rq->search_to)) {
            $post_info = PostInfo::whereDay('post_create', '>=', $day_from)->whereMonth('post_create', '>=', $month_from)->whereDay('post_create', '<=', $day_to)->whereMonth('post_create', '<=', $month_to)->paginate(10);
        } else {
            $post_info = PostInfo::where('message', 'like', "%$name%")->where('page_id', $page_id)->orderBy($filter_att, $order_type)->paginate(10);
        }
        // dd($month_to);
        // $update_at = PostInfo::orderBy('updated_at', 'DESC')->first();
        $update_at = PostInfo::select('updated_at')->first();

        return view('post_info.index', compact('post_info', 'at', 'page_id', 'update_at', 'rq', 'filter'));
    }

    public function create_post_info($id, Request $rq)
    {
        $page_id = $id;
        $post_id = $rq->post_id;
        $at = $rq->access_token;
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://graph.facebook.com/v15.0/' . $page_id . '_' . $post_id . '?fields=reactions.type(ANGRY).limit(0).summary(total_count).as(reactions_angry),reactions.type(SAD).limit(0).summary(total_count).as(reactions_sad),reactions.type(LIKE).limit(0).summary(total_count).as(reactions_like),reactions.type(LOVE).limit(0).summary(total_count).as(reactions_love),reactions.type(WOW).limit(0).summary(total_count).as(reactions_wow),reactions.type(HAHA).limit(0).summary(total_count).as(reactions_haha),shares,permalink_url,message,created_time&access_token=' . $at,
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
        $post_details = json_decode($response);
        // dd($post_details);
        if (isset($post_details->error) || is_null($post_details)) {
            return redirect()->back()->with('msg', 'Id bài viết nhập không tồn tại!');
        } else {
            $reactions_like = $post_details->reactions_like->summary->total_count ?? 0;
            $reactions_sad = $post_details->reactions_sad->summary->total_count ?? 0;
            $reactions_angry = $post_details->reactions_angry->summary->total_count ?? 0;
            $reactions_love = $post_details->reactions_love->summary->total_count ?? 0;
            $reactions_wow = $post_details->reactions_wow->summary->total_count ?? 0;
            $reactions_haha = $post_details->reactions_haha->summary->total_count ?? 0;
            $share = $post_details->shares->count ?? 0;
            $link = $post_details->permalink_url;
            $message = $post_details->message;
            $created_time_raw = date_create($post_details->created_time);
            $created_time = date_format($created_time_raw, "Y-m-d H:i:s");
            // $share = 20;
            $reactions_count = $reactions_like + $reactions_sad + $reactions_angry + $reactions_love + $reactions_wow + $reactions_haha;
            $data = array('page_id' => $page_id, 'post_id' => $post_id, 'message' => $message, 'post_create' => $created_time, 'link' => $link, 'like_count' => $reactions_like, 'angry_count' => $reactions_angry, 'sad_count' => $reactions_sad, 'love_count' => $reactions_love, 'wow_count' => $reactions_wow, 'haha_count' => $reactions_haha, 'shares_count' => $share);

            PostInfo::updateOrCreate(array('post_id' => $post_id), $data);
            return redirect()->back()->with('msg', 'Thêm bài viết thành công');
        }
    }

    public function get_api_all_post(Request $rq)
    {
        $page_id = $rq->page_id;
        $at = Fanpage::select('access_token')->where('page_id', $page_id)->first()->access_token;
        $post_info = PostInfo::where('page_id', $page_id)->get();

        foreach ($post_info as $key) {
            $post_id = $key->post_id;
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://graph.facebook.com/v15.0/' . $page_id . '_' . $post_id . '?fields=reactions.type(ANGRY).limit(0).summary(total_count).as(reactions_angry),reactions.type(SAD).limit(0).summary(total_count).as(reactions_sad),reactions.type(LIKE).limit(0).summary(total_count).as(reactions_like),reactions.type(LOVE).limit(0).summary(total_count).as(reactions_love),reactions.type(WOW).limit(0).summary(total_count).as(reactions_wow),reactions.type(HAHA).limit(0).summary(total_count).as(reactions_haha),shares,permalink_url,message,created_time&access_token=' . $at,
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
            $post_details = json_decode($response);
            // dd($post_details);
            $reactions_like = $post_details->reactions_like->summary->total_count ?? '0';
            $reactions_sad = $post_details->reactions_sad->summary->total_count ?? '0';
            $reactions_angry = $post_details->reactions_angry->summary->total_count ?? '0';
            $reactions_love = $post_details->reactions_love->summary->total_count ?? '0';
            $reactions_wow = $post_details->reactions_wow->summary->total_count ?? '0';
            $reactions_haha = $post_details->reactions_haha->summary->total_count ?? '0';
            $share = $post_details->shares->count ?? '0';
            $link = $post_details->permalink_url ?? '';
            $message = $post_details->message ?? '';
            $created_time_raw = date_create($post_details->created_time ?? '');
            $created_time = date_format($created_time_raw, "Y-m-d H:i:s") ?? '';
            // $share = 20;
            $reactions_count = $reactions_like + $reactions_sad + $reactions_angry + $reactions_love + $reactions_wow + $reactions_haha;
            $data = array('page_id' => $page_id, 'post_id' => $post_id, 'message' => $message, 'post_create' => $created_time, 'link' => $link, 'like_count' => $reactions_like, 'angry_count' => $reactions_angry, 'sad_count' => $reactions_sad, 'love_count' => $reactions_love, 'wow_count' => $reactions_wow, 'haha_count' => $reactions_haha, 'shares_count' => $share);

            PostInfo::updateOrCreate(array('post_id' => $post_id), $data);
        }
        return back()->with('msg', 'Lấy cái bài viết thành công');
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

    public function page_insight(Request $rq)
    {
        $current_year = $rq->year ?? date('Y');
        $current_month = $rq->month ?? date('m');
        $page_id = $rq->page_select;
        // dd($page_id);
        if (isset($page_id)) {
            $page_id = $rq->page_select;
        } else {
            $page_id = FanpageRecord::select('page_id')->orderBy('likes_count', 'DESC')->first()->page_id;
        }
        $all_page = FanpageRecord::select('name', 'page_id')->orderBy('page_id', 'DESC')->distinct()->get();
        // dd($all_page);
        // $all_page = DB::select(DB::raw("SELECT distinct fanpage_record.name,  fanpage_record.page_id, fanpage.picture
        // from fanpage_record
        // inner join fanpage
        // on fanpage_record.page_id = fanpage.page_id
        // order by fanpage.likes_count DESC
        // "));
        $page_record = FanpageRecord::where('page_id', $page_id)->whereMonth('record_date', $current_month)->whereYear('record_date', $current_year)->get();
        return view('insight.fanpage-insight', compact('page_record', 'all_page', 'current_year', 'current_month', 'page_id'));
    }
}
