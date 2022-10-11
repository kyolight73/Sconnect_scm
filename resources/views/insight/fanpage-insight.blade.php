@extends('layouts.master')
@section('content')
    <style>
        /* .d-flex>div {
                                                                                                                                                                                                                                                                                                    margin-right: 20px;
                                                                                                                                                                                                                                                                                                } */
    </style>
    <div class="container">
        {!! App\Utils::createBreadcrumb(['Fanpage', 'Đồ thị']) !!}
        <div class="row">

            {{-- @php
                $t = $current_month;
                $year = $current_year;
                $d;
                switch ($t) {
                    case 1:
                    case 3:
                    case 5:
                    case 7:
                    case 8:
                    case 10:
                    case 12:
                        $d = 31;
                        break;
                    case 4:
                    case 6:
                    case 9:
                    case 11:
                        $d = 30;
                        break;
                    case 2:
                        if ($year % 100 != 0 && $year % 4 == 0) {
                            $d = 29;
                        } else {
                            $d = 28;
                        }
                        break;
                    default:
                        $d = 0;
                }
                echo $d;
            @endphp --}}

            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <form action="{{ route('fanpage.page_insight') }}" method="get">
                            <div class="row mb-3 mt-3">
                                <div class="col-md-4">
                                    <div class="d-flex">
                                        <div>Chọn kênh</div>
                                        <select name="page_select" class="form-control selectw-image"
                                            onchange="this.form.submit()">
                                            @foreach ($all_page as $item)
                                                <option @if ($item->page_id == $page_id) {{ 'selected' }} @endif
                                                    value="{{ $item->page_id }}" data-thumbnail="{{ $item->picture }}">
                                                    {{ $item->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>
                                <div class="col-md-4">
                                    <select name="month" class="form-control" onchange="this.form.submit()">
                                        @for ($i = 1; $i <= 12; $i++)
                                            <option @if ($i == $current_month) {{ 'selected' }} @endif
                                                value="{{ $i }}">Tháng {{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <select name="year" class="form-control" onchange="this.form.submit()">
                                        @for ($i = 2022; $i <= $current_year + 2; $i++)
                                            <option @if ($i == $current_year) {{ 'selected' }} @endif
                                                value="{{ $i }}">Năm {{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>


                        </form>

                        <h5 style="text-align: center">Đồ thị thống kê lượng Likes tháng {{ $current_month }} năm
                            {{ $current_year }} </h5>

                    </div>
                    <div>
                        <canvas id="myChart"></canvas>
                    </div>
                    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                </div>
            </div>
        </div>
    </div>
    <script>
        // $(document).ready(function() {
        const labels = [
            <?php
            // for ($i = 1; $i <= $d; $i++) {
            //     echo $i . ',';
            // }
            foreach ($page_record as $key) {
                $days = strtotime($key->record_date);
                $day_format = date('d', $days);
                $days_get = $day_format . ',';
                echo $days_get;
            }
            ?>
        ];

        const data = {
            labels: labels,
            datasets: [{
                label: 'Lượt likes',
                backgroundColor: 'rgb(255, 99, 132)',
                borderColor: 'rgb(255, 99, 132)',
                data: [
                    <?php
                    foreach ($page_record as $key) {
                        echo $key->likes_count . ',';
                    }
                    ?>
                ],
            }]
        };

        const config = {
            type: 'line',
            data: data,
            options: {}
        };
        // });
    </script>
    <script>
        const myChart = new Chart(
            document.getElementById('myChart'),
            config
        );
    </script>
@endsection
