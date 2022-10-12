<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shortlink;
use App\Models\ShortlinkCountryTemp;
use App\Models\ShortlinkRecord;
use App\Models\ShortlinkThemeRecord;
use Illuminate\Support\Facades\DB;

class ShortlinkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $rq)
    {
        // $current_month = date('m');
        $all_user = Shortlink::select('created_by')->distinct()->get();
        $user_create = $rq->user_create;
        // dd($user_create);
        $current_month = $rq->month ?? date('m');
        $name = $rq->name;
        $theme = $rq->theme;
        $filter = $rq->filter;
        switch ($filter) {
            case 'click_desc':
                $filter_att = 'click_count';
                $order_type = 'DESC';
                break;
            case 'click_asc':
                $filter_att = 'click_count';
                $order_type = 'ASC';
                break;
            case 'desc':
                $filter_att = 'link_date';
                $order_type = 'DESC';
                break;
            case 'asc':
                $filter_att = 'link_date';
                $order_type = 'ASC';
                break;
            default:
                $filter_att = 'click_count';
                $order_type = 'DESC';
                break;
        }
        // dd($current_month);

        if (isset($filter) || isset($user_create) || isset($theme)) {
            $shortlink = Shortlink::where('link_id', 'like', "%$name%")->where('created_by', 'like', "%$user_create%")->where('title', 'like', "%$theme%")->whereMonth('link_date', $current_month)->orderBy($filter_att, $order_type)->paginate(20);
        } else {
            $shortlink = Shortlink::orderBy($filter_att, $order_type)->paginate(20);
        }
        $update_at = Shortlink::select('updated_at')->orderBy('updated_at', 'DESC')->first() ?? 'Initial';
        return view('shortlink.index', compact('shortlink', 'name', 'current_month', 'all_user', 'user_create', 'filter', 'theme', 'update_at'));
    }

    public function get_newest_link(Request $rq)
    {
        set_time_limit(0);
        $guid = Shortlink::select('guid', 'access_token')->distinct()->get();
        $latest_link = Shortlink::select('link_date')->orderBy('link_date', 'DESC')->first()->link_date;
        $current_month = date('m');
        $link_month = date('m', strtotime($latest_link));
        $link_day = date('d', strtotime($latest_link));
        $created_after = strtotime($link_month . '/' . $link_day . '/2022 12:00:00 AM');
        $created_before = strtotime($current_month . '/31/2022 11:59:59 PM');
        foreach ($guid as $group) {
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => 'https://api-ssl.bitly.com/v4/groups/' . $group->guid . '/bitlinks?size=100&page=1&created_before=' . $created_before . '&created_after=' . $created_after,
                // CURLOPT_URL => 'https://api-ssl.bitly.com/v4/groups/' . $guidz . '/bitlinks?size=100&page=1',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => ['Authorization: Bearer ' . $group->access_token],
            ]);

            $response = curl_exec($curl);

            $all_links = json_decode($response);
            // dd($all_links);

            // Check số trang
            $total = $all_links->pagination->total;
            $page = 1;
            for ($x = 100; $x <= $total; $x += 100) {
                $page += 1;
            }
            if ($total == 0) {
                return redirect()->back()->with('msg', 'Hiện chưa có thêm link nào mới trong tháng này!');
            }

            // Lấy tổng các link của các group theo tháng
            for ($i = 1; $i <= $page; $i++) {
                $curl = curl_init();
                curl_setopt_array($curl, [
                    CURLOPT_URL => 'https://api-ssl.bitly.com/v4/groups/' . $group->guid . '/bitlinks?size=100&page=' . $i . '&created_before=' . $created_before . '&created_after=' . $created_after,
                    // CURLOPT_URL => 'https://api-ssl.bitly.com/v4/groups/' . $guidz . '/bitlinks?size=100&page=' . $i,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'GET',
                    CURLOPT_HTTPHEADER => ['Authorization: Bearer ' . $group->access_token],
                ]);

                $response = curl_exec($curl);

                // curl_close($curl);
                $all_links = json_decode($response);

                foreach ($all_links->links as $key) {
                    $str = $key->id;
                    $strim = str_replace("bit.ly/", "", $str);
                    // dd($strim);

                    // if ($month == $current_month || $month = ($current_month - 1)) {
                    $url = 'https://api-ssl.bitly.com/v4/bitlinks/bit.ly/' . $strim . '/clicks/summary';

                    $curl = curl_init();
                    curl_setopt_array($curl, [
                        CURLOPT_URL => $url,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'GET',
                        CURLOPT_HTTPHEADER => ['Authorization: Bearer ' . $group->access_token],
                    ]);

                    $response = curl_exec($curl);
                    curl_close($curl);
                    $clicks_count = json_decode($response);
                    // Cắt bớt date
                    $day = substr($key->created_at, 0, -14);
                    $click_count = $clicks_count->total_clicks ?? '0';
                    $title = $key->title ?? 'Business';
                    // Truyền dữ liệu
                    $data = array(
                        'organization_guid' => 'og', 'guid' => $group->guid, 'link_id' => $key->id, 'title' => $title, 'access_token' => $group->access_token, 'link_date' => $day, 'created_by' => $key->created_by,
                        'short_url' => $key->link, 'long_url' => $key->long_url, 'click_count' => $click_count
                    );
                    Shortlink::updateOrCreate(array('link_id' => $key->id), $data);
                }
            }
        }
        return back()->with('msg', 'Lấy các link mới thành công!');
    }

    public function create_bitly_api(Request $rq)
    {
        set_time_limit(0);
        // Xác định ngày tháng năm
        $current_year = date('Y');
        $current_month = date('m');
        $current_day = date('d');
        $current_date = date('Y-m-d');
        // Lấy tổng các Group của một user
        $curl = curl_init();
        $organization_guid = $rq->user_organization_guid;
        $user_access_token = $rq->user_access_token;
        // $link_number = $rq->link_number;
        // $month = $rq->month;
        // if ($link_number <= 100) {
        //     $size = $link_number;
        // } else {
        //     $size = 100;
        // }

        // dd($user_access_token);
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api-ssl.bitly.com/v4/groups?organization_guid=' . $organization_guid,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $user_access_token
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $all_groups = json_decode($response);
        if (isset($all_groups->message) || empty($all_groups->groups)) {
            return redirect()->back()->with('msg', 'Xảy ra lỗi khi gọi User bitlink!');
        }
        // dd($all_groups);
        foreach ($all_groups->groups as $item) {

            $created_after = strtotime($current_month . '/01/2022 12:00:00 AM');
            $created_before = strtotime($current_month . '/31/2022 11:59:59 PM');
            $curl = curl_init();
            $guidz = $item->guid;
            curl_setopt_array($curl, [
                CURLOPT_URL => 'https://api-ssl.bitly.com/v4/groups/' . $guidz . '/bitlinks?size=100&page=1&created_before=' . $created_before . '&created_after=' . $created_after,
                // CURLOPT_URL => 'https://api-ssl.bitly.com/v4/groups/' . $guidz . '/bitlinks?size=100&page=1',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => ['Authorization: Bearer ' . $user_access_token],
            ]);

            $response = curl_exec($curl);

            $all_links = json_decode($response);
            // Check số trang
            $total = $all_links->pagination->total;
            // dd($total);
            if ($total == 0) {
                return redirect()->back()->with('msg', 'Hiện tài khoản này chưa có bitlink nào được tạo trong tháng này!');
            }
            $page = 1;
            for ($x = 100; $x <= $total; $x += 100) {
                $page += 1;
            }

            // Lấy tổng các link của các group theo tháng
            for ($i = 1; $i <= $page; $i++) {
                $curl = curl_init();
                $guidz = $item->guid;
                curl_setopt_array($curl, [
                    CURLOPT_URL => 'https://api-ssl.bitly.com/v4/groups/' . $guidz . '/bitlinks?size=100&page=' . $i . '&created_before=' . $created_before . '&created_after=' . $created_after,
                    // CURLOPT_URL => 'https://api-ssl.bitly.com/v4/groups/' . $guidz . '/bitlinks?size=100&page=' . $i,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'GET',
                    CURLOPT_HTTPHEADER => ['Authorization: Bearer ' . $user_access_token],
                ]);

                $response = curl_exec($curl);

                // curl_close($curl);
                $all_links = json_decode($response);

                // Lấy lượt click của từng link
                // if ($month == $current_month) {
                //     $day_set = $current_day;
                //     $current_month = str_pad($current_month + 1, 2, "0", STR_PAD_LEFT);
                // } else {
                //     $day_set = '30';
                // }
                // $des_time = $current_year . '-' . str_pad($current_month - 1, 2, "0", STR_PAD_LEFT) . '-' . $day_set;

                foreach ($all_links->links as $key) {
                    $str = $key->id;
                    $strim = str_replace("bit.ly/", "", $str);
                    // dd($strim);

                    // if ($month == $current_month || $month = ($current_month - 1)) {
                    $url = 'https://api-ssl.bitly.com/v4/bitlinks/bit.ly/' . $strim . '/clicks/summary';
                    // }
                    // else {
                    //     $url = 'https://api-ssl.bitly.com/v4/bitlinks/bit.ly/' . $strim . '/clicks/summary?unit=month&units=1&unit_reference=' . $des_time . 'T01%3A00%3A00-0700';
                    // }
                    // Thực thi lệnh gọi API
                    $curl = curl_init();
                    curl_setopt_array($curl, [
                        CURLOPT_URL => $url,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'GET',
                        CURLOPT_HTTPHEADER => ['Authorization: Bearer ' . $user_access_token],
                    ]);

                    $response = curl_exec($curl);
                    curl_close($curl);
                    $clicks_count = json_decode($response);
                    // Cắt bớt date
                    $day = substr($key->created_at, 0, -14);
                    $click_count = $clicks_count->total_clicks ?? '0';
                    $title = $key->title ?? 'Business';
                    // Truyền dữ liệu
                    $data = array(
                        'organization_guid' => $organization_guid, 'guid' => $guidz, 'link_id' => $key->id, 'title' => $title, 'access_token' => $user_access_token, 'link_date' => $day, 'created_by' => $key->created_by,
                        'short_url' => $key->link, 'long_url' => $key->long_url, 'click_count' => $click_count
                    );

                    Shortlink::updateOrCreate(array('link_id' => $key->id), $data);
                }
            }
        }
        return back()->with('msg', 'Thêm user bitlink thành công!');
    }

    public function get_update_all_link()
    {
        set_time_limit(0);
        $shortlink = Shortlink::get();
        foreach ($shortlink as $key) {
            $current_year = date('Y');
            $current_month = date('m');
            $current_day = date('d');
            $record_curr_monthz = strtotime($key->link_date);
            $record_curr_month = date('m', $record_curr_monthz);
            $linkz = $key->link_id;
            $link = str_replace("bit.ly/", "", $linkz);
            $day_set = 30;
            $des_time = $current_year . '-' . str_pad($current_month - 1, 2, "0", STR_PAD_LEFT) . '-' . $day_set;
            // Thực thi lệnh gọi API
            if ($record_curr_month == $current_month || $record_curr_month == str_pad($current_month - 1, 2, "0", STR_PAD_LEFT)) {
                $url = 'https://api-ssl.bitly.com/v4/bitlinks/bit.ly/' . $link . '/clicks/summary';
            } else {
                $url = 'https://api-ssl.bitly.com/v4/bitlinks/bit.ly/' . $link . '/clicks/summary?unit=month&units=1&unit_reference=' . $des_time . 'T01%3A00%3A00-0700';
            }

            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => ['Authorization: Bearer ' . $key->access_token],
            ]);

            $response = curl_exec($curl);
            curl_close($curl);
            $clicks_count = json_decode($response);
            Shortlink::where('link_id', $linkz)->update(['click_count' => $clicks_count->total_clicks]);
        }
        return back()->with('msg', 'Đã cập nhật số lượt click các bitlink hiện tại thành công!');
    }

    public function create_bitly_link_api(Request $rq)
    {
        $link_id = $rq->link_id;
        $user_access_token = $rq->user_access_token;
        $organization_guid = $rq->organization_guid;
        $current_year = date('Y');
        $current_month = date('m');
        $current_day = date('d');
        // Lấy link info
        $url = 'https://api-ssl.bitly.com/v4/bitlinks/bit.ly/' . $link_id;
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => ['Authorization: Bearer ' . $user_access_token],
        ]);

        $response = curl_exec($curl);
        curl_close($curl);
        $link_info = json_decode($response);
        // dd($link_info);
        $create_info = strtotime($link_info->created_at);
        $month = date('m', $create_info);
        $query_month = str_pad($current_month - 1, 2, "0", STR_PAD_LEFT);

        if ($month == $current_month) {
            $url = 'https://api-ssl.bitly.com/v4/bitlinks/bit.ly/' . $link_id . '/clicks/summary';
        } else {
            $url = 'https://api-ssl.bitly.com/v4/bitlinks/bit.ly/' . $link_id . '/clicks/summary?unit=month&units=1&unit_reference=2022-' . $query_month . '-30T01%3A00%3A00-0700';
        }
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => ['Authorization: Bearer ' . $user_access_token],
        ]);

        $response = curl_exec($curl);
        curl_close($curl);
        $clicks_count = json_decode($response);
        $total_click = $clicks_count->total_clicks ?? '0';
        $guid_get = $link_info->references->group;
        $guid = trim($guid_get, 'https://api-ssl.bitly.com/v4/groups/');
        $day = substr($link_info->created_at, 0, -14);
        $title = $link_info->title ?? 'Business';
        // Nhập dữ liệu vào DB
        $data = array(
            'organization_guid' => $organization_guid, 'guid' => $guid, 'link_id' => $link_info->id, 'title' => $title, 'access_token' => $user_access_token, 'link_date' => $day, 'created_by' => $link_info->created_by,
            'short_url' => $link_info->link, 'long_url' => $link_info->long_url, 'click_count' => $total_click
        );
        Shortlink::updateOrCreate(array('link_id' => $link_info->id), $data);
        return back();
    }

    public function shortlink_country_group_insight(Request $rq)
    {
        //Lấy các user hiện tại
        $user_group = Shortlink::select('created_by', 'access_token', 'guid')->distinct()->get();

        //Thực hiện lệnh query lấy thông số lượt click trên các quốc gia
        foreach ($user_group as $key) {
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api-ssl.bitly.com/v4/groups/' . $key->guid . '/countries',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . $key->access_token
                ),
            ));

            $response = curl_exec($curl);
            $result = json_decode($response);
            curl_close($curl);
            $record_date_string = strtotime($result->unit_reference);
            $record_date = date("Y", $record_date_string) . "-" . date("m", $record_date_string) . "-" . date("d", $record_date_string);
            //Lấy thông số của từng kết quả tìm được
            foreach ($result->metrics as $item) {
                $data = array('group_id' => $key->guid, 'country_code' => $item->value, 'record_reference' => $record_date, 'click_count' => $item->clicks);
                ShortlinkCountryTemp::updateOrCreate(array('country_code' => $item->value), $data);
            }
        }
        return back();
    }

    public function shortlink_insight(Request $rq)
    {
        // $user_name = DB::select(DB::raw('SELECT DISTINCT shortlink.created_by from shortlink inner join shortlink_country_temp on shortlink_country_temp.group_id = shortlink.guid'));
        $get_group_id = ShortlinkCountryTemp::select('group_id')->orderBy('click_count', 'DESC')->where('click_count', '69696969696969')->first()->group_id ?? '';
        $group_id = $rq->group_id ?? $get_group_id;
        $select_group_id = Shortlink::select('created_by', 'access_token', 'guid')->distinct()->get();
        $limit = $rq->limit ?? 15;
        $filter = $rq->filter ?? 'desc';
        switch ($filter) {
            case 'desc':
                $filter = 'desc';
                break;

            case 'desc':
                break;
        }
        $shortlink_country_record = ShortlinkCountryTemp::where('group_id', $group_id)->orderBy('click_count', $filter)->limit($limit)->get();
        $update_at = ShortlinkCountryTemp::select('updated_at')->orderBy('updated_at', 'DESC')->first() ?? 'Initial';
        return view('insight.shortlink-insight', compact('shortlink_country_record', 'group_id', 'select_group_id', 'limit', 'filter', 'update_at'));
    }

    public function shortlink_record()
    {
        set_time_limit(0);
        array('wolfoo', 'animated', 'Tiny', 'Max', 'Lego', 'Fairy tales', 'Paper doll', 'Paper motion', 'Baby', 'Music', 'Cake', 'Parody');
        $all_theme = array('wolfoo', 'animated', 'Tiny', 'Max', 'Lego', 'Fairy tales', 'Paper doll', 'Paper motion', 'Baby', 'Music', 'Cake', 'Parody');
        foreach ($all_theme as $theme) {
            $get_link_id = Shortlink::select('link_id', 'access_token', 'guid')->where('title', 'like', "%$theme%")->orderBy('click_count', 'DESC')->get();
            foreach ($get_link_id as $key) {
                $str = $key->link_id;
                $link_id = str_replace("bit.ly/", "", $str);
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://api-ssl.bitly.com/v4/bitlinks/bit.ly/' . $link_id . '/countries',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'GET',
                    CURLOPT_HTTPHEADER => array(
                        'Authorization: Bearer ' . $key->access_token
                    ),
                ));

                $response = curl_exec($curl);

                curl_close($curl);
                $result = json_decode($response);
                // $ar = get_object_vars($result);
                // dd($ar);
                foreach ($result->metrics as $click) {
                    $click_count = $click->clicks;
                    $value = $click->value;
                    $record_date_string = strtotime($result->unit_reference);
                    $record_date = date("Y", $record_date_string) . "-" . date("m", $record_date_string) . "-" . date("d", $record_date_string);
                    $data = array('link_id' => $link_id, 'country_code' => $value, 'guid' => $key->guid, 'click_count' => $click_count, 'title' => $theme, 'record_reference' => $record_date);
                    // dd($data);
                    // print_r($data);
                    ShortlinkRecord::updateOrCreate(array('link_id' => $link_id, 'country_code' => $value), $data);
                }
            }
        }
        $country_code = ShortlinkRecord::select('country_code')->distinct()->get();
        $all_theme = array('wolfoo', 'animated', 'Tiny', 'Max', 'Lego', 'Fairy tales', 'Paper doll', 'Paper motion', 'Baby', 'Music', 'Cake', 'Parody');
        foreach ($all_theme as $theme) {
            foreach ($country_code as $country) {
                $country_code_name = $country->country_code;
                $guid = ShortlinkRecord::select('guid')->where('country_code', 'like', "%$country_code_name%")->where('title', 'like', "%$theme%")->groupBy('guid')->first();
                $sum_click = ShortlinkRecord::select(DB::raw('sum(click_count) as total_click'))->where('country_code', 'like', "%$country_code_name%")->where('title', 'like', "%$theme%")->first();
                $data = array('country_code' => $country_code_name, 'click_count' => $sum_click->total_click ?? 0, 'guid' => $guid->guid ?? 'none', 'title' => $theme, 'record_date' => date('Y-m-d'));
                ShortlinkThemeRecord::updateOrCreate(array('title' => $theme, 'country_code' => $country_code_name), $data);
            }
        }
        $find_null = ShortlinkThemeRecord::where('guid', 'none')->delete();
    }

    public function shortlink_record_index(Request $rq)
    {
        $limit = $rq->limit ?? 15;
        $theme = $rq->theme ?? 'Wolfoo';
        $all_theme = array('Wolfoo', 'Animated', 'Tiny', 'Max', 'Lego', 'Fairy tales', 'Paper doll', 'Paper motion', 'Baby', 'Music', 'Cake', 'Parody');
        $filter = $rq->filter ?? 'desc';
        switch ($filter) {
            case 'desc':
                $filter = 'desc';
                break;

            case 'desc':
                break;
        }
        $get_user = DB::select(DB::raw('SELECT DISTINCT shortlink.created_by, shortlink.guid from shortlink INNER JOIN shortlink_theme_record ON shortlink.guid = shortlink_theme_record.guid'));
        $user = $rq->user ?? '';
        // dd($get_user);
        if ($user != '') {
            $theme_click = ShortlinkThemeRecord::where('title', 'like', "%$theme%")->where('guid', $user)->limit($limit)->orderBy('click_count', $filter)->get();
        } else {
            $theme_click = ShortlinkThemeRecord::where('title', 'like', "%$theme%")->limit($limit)->orderBy('click_count', $filter)->get();
        }
        $update_at = ShortlinkThemeRecord::select('updated_at')->orderBy('updated_at', 'DESC')->first() ?? 'Initial';
        return view('insight.shortlink-record-insight', compact('all_theme', 'limit', 'theme_click', 'theme', 'filter', 'get_user', 'user', 'update_at'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
}
