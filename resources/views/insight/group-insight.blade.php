@extends('layouts.master')
@section('content')
    <style>
        /* .d-flex>div {
                                                                                                                                margin-right: 20px;
                                                                                                                            } */
    </style>
    <div class="container">
        {!! App\Utils::createBreadcrumb(['Group', 'Đồ thị']) !!}
        <div class="row">

            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <form action="{{ route('group.group_insight') }}" method="get">
                            <div class="row mb-3 mt-3">
                                <div class="col-md-4">
                                    <div class="d-flex">
                                        <div>Chọn kênh</div>
                                        <select name="group_select" class="form-control" onchange="this.form.submit()">
                                            @foreach ($all_group as $item)
                                                <option @if ($item->group_id == $group_id) {{ 'selected' }} @endif
                                                    value="{{ $item->group_id }}"> {{ $item->name }} </option>
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

                        <h5 style="text-align: center">Đồ thị thống kê lượng Theo dõi tháng {{ $current_month }} năm
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
            foreach ($group_record as $key) {
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
                label: 'Lượt theo dõi',
                backgroundColor: 'rgb(255, 99, 132)',
                borderColor: 'rgb(255, 99, 132)',
                data: [
                    <?php
                    foreach ($group_record as $key) {
                        echo $key->member_count . ',';
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
