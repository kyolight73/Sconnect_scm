@extends('layouts.master')
@section('content')
    <div class="container">
        {!! App\Utils::createBreadcrumb(['Shortlink', 'Đồ thị tổng số lượt click']) !!}
        <div class="">
            <p class="status-info">Dữ liệu được cập nhật vào lúc {{ $update_at->updated_at ?? '' }} </p>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Chọn các dạng biểu đồ</h3>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('shortlink.shortlink_insight') }}" method="get">
                                    <label class="margin-top">Chọn tài khoản</label>
                                    <select name="group_id" class="form-control selectw-image"
                                        onchange="this.form.submit()">
                                        @foreach ($select_group_id as $item)
                                            <option @if ($item->guid == $group_id) {{ 'selected' }} @endif
                                                value="{{ $item->guid }}">
                                                {{ $item->created_by }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <label class="margin-top">Sắp xếp thứ tự</label>
                                    <select name="filter" class="form-control selectw-image" onchange="this.form.submit()">
                                        <option @if ($filter == 'desc') {{ 'selected' }} @endif value="desc">
                                            Nhiều đến ít</option>
                                        <option @if ($filter == 'asc') {{ 'selected' }} @endif value="asc">
                                            Ít tới nhiều</option>
                                    </select>
                                    <label class="margin-top">Số lượng hiển thị</label>
                                    <select name="limit" class="form-control selectw-image" onchange="this.form.submit()">
                                        <option @if ($limit == 15) {{ 'selected' }} @endif value="15">
                                            Hiển thị tiêu chuẩn</option>
                                        <option @if ($limit == 99) {{ 'selected' }} @endif value="99">
                                            Hiển thị đầy đủ</option>
                                    </select>
                                </form>
                                <a href="{{ route('shortlink.shortlink_record_index') }}" class="btn btn-success mt-3">Đồ
                                    thị theo nội
                                    dung</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-body">
                                <h5 style="text-align: center">Đồ thị thống kê lượt click các quốc gia trong 30 ngày</h5>
                                <div style="height: 450px;position: relative;border:0;">
                                    <canvas id="myChart" width="450" height="450"
                                        style="width: 450px; height: 450px; display: block; box-sizing: border-box;"></canvas>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
                <a href="{{ route('shortlink.shortlink_country_group_insight') }}">Get</a>
                <a href="{{ route('shortlink.shortlink_record') }}">Get API</a>

                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                <script>
                    const labels = [
                        <?php
                        foreach ($shortlink_country_record as $key) {
                            echo "'$key->country_code',";
                        }
                        ?>
                    ];

                    const data = {
                        labels: labels,
                        datasets: [{
                            label: 'Lượt click',
                            backgroundColor: 'rgb(255, 99, 132)',
                            borderColor: 'rgb(255, 99, 132)',
                            data: [
                                <?php
                                foreach ($shortlink_country_record as $key) {
                                    echo $key->click_count . ',';
                                }
                                ?>
                            ],
                        }]
                    };

                    const config = {
                        type: 'bar',
                        data: data,
                        options: {
                            maintainAspectRatio: false,
                        }
                    };
                </script>
                <script>
                    const myChart = new Chart(
                        document.getElementById('myChart'),
                        config
                    );
                </script>
            </div>
        </div>
    </div>
@endsection
