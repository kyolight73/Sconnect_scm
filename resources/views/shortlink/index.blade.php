@extends('layouts.master')
@section('content')
    <style>
        table tr td,
        table tr th {
            vertical-align: middle !important;
        }
    </style>
    <div class="container">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><i class="fas fa-home" aria-hidden="true"></i> Marketing</li>
            <li class="breadcrumb-item">Shortlink</li>
        </ul>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Danh sách các shortlink</h5>

                        <div id="fb-root"></div>
                        <script async defer crossorigin="anonymous"
                            src="https://connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v14.0&appId=615532730010827&autoLogAppEvents=1"
                            nonce="UbbJ5EFS"></script>
                    </div>
                    <div class="card-body table-responsive p-0">

                        @foreach ($all_groups->groups as $item)
                            @php
                                // Lấy tổng các link của các group
                                $curl = curl_init();
                                $guidz = $item->guid;
                                curl_setopt_array($curl, [
                                    CURLOPT_URL => 'https://api-ssl.bitly.com/v4/groups/' . $guidz . '/bitlinks?size=10&page=1',
                                    CURLOPT_RETURNTRANSFER => true,
                                    CURLOPT_ENCODING => '',
                                    CURLOPT_MAXREDIRS => 10,
                                    CURLOPT_TIMEOUT => 0,
                                    CURLOPT_FOLLOWLOCATION => true,
                                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                    CURLOPT_CUSTOMREQUEST => 'GET',
                                    CURLOPT_HTTPHEADER => ['Authorization: Bearer b0e4a16e9433c720eacf9ffae42091cb64c1fd43'],
                                ]);

                                $response = curl_exec($curl);

                                curl_close($curl);
                                $all_links = json_decode($response);
                            @endphp
                            <br>
                            <h5 style="text-transform:uppercase; padding: 0 20px;">Shortlink của Group
                                {{ $item->name }} </h5><br>
                            <table class="table table-hover" border="0">
                                <thead>
                                    <tr style="background-color: #dee2e6">
                                        <th style="width: 125px;">Date</th>
                                        <th style="width: 100px;">Tạo bởi</th>
                                        <th>Link ngắn</th>
                                        <th>Đích URL</th>
                                        <th>Lượt click</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($all_links->links as $key)
                                        @php
                                            // Lấy tổng lượt click
                                            $str = $key->link;
                                            $strim = trim($str, 'https://bit.ly/!');
                                            $url = 'https://api-ssl.bitly.com/v4/bitlinks/bit.ly/' . $strim . '/clicks?';

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
                                                CURLOPT_HTTPHEADER => ['Authorization: Bearer b0e4a16e9433c720eacf9ffae42091cb64c1fd43'],
                                            ]);

                                            $response = curl_exec($curl);
                                            curl_close($curl);
                                            $clicks_count = json_decode($response);
                                            // Cắt bớt date
                                            $day = substr($key->created_at, 0, -14);
                                        @endphp
                                        <tr>
                                            <td> {{ $day }} </td>
                                            <td> {{ $key->created_by }}</td>
                                            <td> {{ $key->link }} </td>
                                            <td> {{ $key->long_url }} </td>
                                            <td> {{ array_sum(array_column($clicks_count->link_clicks, 'clicks')) }} </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endforeach
                    </div>
                </div>
                <!-- /.card -->
            </div>
        </div>
    </div>
@endsection
