<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GroupModel;
use App\Models\GroupRecord;
use Illuminate\Support\Facades\DB;


class GroupController extends Controller
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
            case 'member_count_desc':
                $filter_att = 'member_count';
                $order_type = 'DESC';
                break;
            case 'member_count_asc':
                $filter_att = 'member_count';
                $order_type = 'ASC';
                break;
            default:
                $filter_att = 'member_count';
                $order_type = 'DESC';
                break;
        }
        $group = GroupModel::where('name', 'like', "%$name%")->orWhere('group_id', 'like', "%$name%")->orderby($filter_att, $order_type)->paginate(10);
        $update_at = GroupModel::select('updated_at')->orderBy('updated_at', 'DESC')->first() ?? 'Initial';
        return view('group.index', compact('group', 'update_at', 'filter'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $rq)
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $rq)
    {
        $at = $this->facebook_access_token;
        $link = $rq->link;
        $group_id = trim($link, 'https://www.facebook.com/groups/');
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://graph.facebook.com/v15.0/' . $group_id . '?fields=member_count,name,picture&access_token=' . $at,
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
        $group_detail = json_decode($response);
        if (isset($group_detail->error) || is_null($group_detail)) {
            return redirect()->back()->with('msg', 'Xảy ra lỗi khi gọi group!');
        }
        $group_name = $group_detail->name;
        $group_member_count = $group_detail->member_count;

        $data = array('group_id' => $group_id, 'link' => $link, 'name' => $group_name, 'picture' => $group_detail->picture->data->url, 'member_count' => $group_member_count);

        GroupModel::updateOrCreate(array('group_id' => $group_id), $data);
        return back()->with('msg', 'Thêm group thành công');
    }

    public function get_api_all_group(Request $rq)
    {
        $at = $this->facebook_access_token;
        $group_info = GroupModel::get();
        foreach ($group_info as $key) {
            $group_id = $key->group_id;
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://graph.facebook.com/v15.0/' . $group_id . '?fields=member_count,name,picture&access_token=' . $at,
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
            $group_detail = json_decode($response);
            // dd($group_detail);
            if (isset($group_detail->error)) {
                return back()->with('msg', 'Xảy ra lỗi trong quá trình gọi API, hãy kiểm tra lại Access Token!');
            }
            $group_name = $group_detail->name;
            $group_member_count = $group_detail->member_count;

            $data = array('group_id' => $group_id, 'link' => $key->link, 'name' => $group_name, 'picture' => $group_detail->picture->data->url, 'member_count' => $group_member_count);

            GroupModel::updateOrCreate(array('group_id' => $group_id), $data);

            // Lưu data vào record
            $record_date = date('Y-m-d');
            $record = array('record_date' => $record_date, 'group_id' => $group_id, 'member_count' => $group_member_count, 'name' => $group_name);
            $check_exist = GroupRecord::where('group_id', $group_id)->where('record_date', $record_date)->count();
            if ($check_exist == 0) {
                $fanpage_record = GroupRecord::create($record);
            }
        }
        return back()->with('msg', 'Lấy Group thành công!');
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
        //
    }

    public function group_insight(Request $rq)
    {
        $current_year = $rq->year ?? date('Y');
        $current_month = $rq->month ?? date('m');
        $group_id = $rq->group_select;
        // dd($group_id);
        if (isset($group_id)) {
            $group_id = $rq->group_select;
        } else {
            $group_id = GroupRecord::select('group_id')->orderBy('member_count', 'DESC')->first()->group_id;
        }
        $all_group = GroupRecord::select('name', 'group_id')->distinct()->orderBy('group_id', 'DESC')->get();
        $group_record = GroupRecord::where('group_id', $group_id)->whereMonth('record_date', $current_month)->whereYear('record_date', $current_year)->get();
        return view('insight.group-insight', compact('group_record', 'all_group', 'current_year', 'current_month', 'group_id'));
    }
}
