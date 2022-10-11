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
        {{-- <input class="form-control" type="text" id="test_input" name="test_input">
        <button class="btn btn-success" type="submit" id="test_button">Click</button> --}}
        {{-- <script>
            $(document).ready(function() {
                $('#test_button').on('click', function() {
                    var prefix_name = $('#test_input').val();
                    if (prefix_name.trim() === '') {
                        toastr.error('Bạn không nhập gì à?');
                        return;
                    }

                });
            });
        </script> --}}
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Danh sách các shortlink</h5>
                        <div class="d-flex justify-content-between">
                            <div class="left-content d-flex">
                                <a id="clicks" class="btn btn-info mr-3"
                                    href=" {{ route('shortlink.get_update_all_link') }} ">
                                    Update lượt click
                                    <span style="display: none;" class="spinner-border spinner-border-sm" role="status"
                                        aria-hidden="true"></span>
                                </a>
                                <form action=" {{ route('shortlink.get_newest_link') }} " method="post">
                                    @csrf
                                    <button id="bitlink" class="btn btn-success mr-3" type="submit">
                                        Update link mới nhất
                                        <span style="display: none;" class="spinner-border spinner-border-sm" role="status"
                                            aria-hidden="true"></span>
                                    </button>
                                </form>

                                <a class="btn btn-outline-primary mr-3" href=" {{ route('shortlink.shortlink_insight') }} ">
                                    Đồ thị lượt click
                                </a>
                                <a class="btn btn-outline-primary mr-3"
                                    href=" {{ route('shortlink.shortlink_record_index') }} ">
                                    Đồ thị nội dung
                                </a>
                                <span class="cursor-hand btn-round mr-3" data-toggle="modal"
                                    data-target="#modal_add_organization" data-whatever="" data-whatid="">
                                    <i class="fas fa-plus"></i> Thêm User theo group</span>
                                <span class="cursor-hand btn-round" data-toggle="modal"
                                    data-target="#modal_add_link_organization" data-whatever="" data-whatid="">
                                    <i class="fas fa-plus"></i> Thêm link</span>
                            </div>

                            <div class="right-content d-flex align-items-center"
                                style="flex: auto;justify-content: flex-end;">

                            </div>
                        </div>
                        <p class="status-info">Dữ liệu được cập nhật vào lúc {{ $update_at->updated_at ?? '' }} </p>
                        <div id="fb-root"></div>
                        <h6 class="mt-3">Lọc kết quả click</h6>
                        <form action="" method="GET" class="mt-2">
                            <div class="row search-holder">
                                <div class="col-md-4">
                                    <select name="user_create" class="form-control">
                                        <option value="">Tất cả tài khoản</option>
                                        @foreach ($all_user as $item)
                                            <option @if ($item->created_by == $user_create) {{ 'selected' }} @endif
                                                value="{{ $item->created_by }}">{{ $item->created_by }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <select name="month" class="form-control">
                                        <option value="">Mọi thời điểm</option>
                                        @for ($i = 1; $i <= 12; $i++)
                                            <option @if ($i == $current_month) {{ 'selected' }} @endif
                                                value="{{ $i }}">Tháng {{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <select name="filter" class="form-control" style="">
                                        <option @if ($filter == 'click_desc') {{ 'selected' }} @endif
                                            value="click_desc">
                                            Click nhiều nhất</option>
                                        <option @if ($filter == 'click_asc') {{ 'selected' }} @endif
                                            value="click_asc">
                                            Click ít nhất</option>
                                        <option @if ($filter == 'desc') {{ 'selected' }} @endif
                                            value="desc">
                                            Mới nhất</option>
                                        <option @if ($filter == 'asc') {{ 'selected' }} @endif
                                            value="asc">
                                            Cũ nhất</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-4">
                                    <input type="text" name="name" class="form-control"
                                        placeholder="Tìm kiếm Tên Page hoặc Id">
                                </div>
                                <div class="col-md-4">
                                    <select name="theme" class="form-control">
                                        <option value="">Tất cả chủ đề</option>
                                        <option @if ($theme == 'Wolfoo') {{ 'selected' }} @endif
                                            value="Wolfoo">Wolfoo</option>
                                        <option @if ($theme == 'Max') {{ 'selected' }} @endif
                                            value="Max">Max</option>
                                        <option @if ($theme == 'Tiny') {{ 'selected' }} @endif
                                            value="Tiny">Tiny</option>
                                        <option @if ($theme == 'Lego') {{ 'selected' }} @endif
                                            value="Lego">Lego</option>
                                        <option @if ($theme == 'Animated') {{ 'selected' }} @endif
                                            value="Animated">Animated</option>
                                        <option @if ($theme == 'Fairy tales') {{ 'selected' }} @endif
                                            value="Fairy tales">Fairy tales</option>
                                        <option @if ($theme == 'Paper doll') {{ 'selected' }} @endif
                                            value="Paper doll">Paper doll</option>
                                        <option @if ($theme == 'Paper motion') {{ 'selected' }} @endif
                                            value="Paper motion">Paper motion</option>
                                        <option @if ($theme == 'Baby') {{ 'selected' }} @endif
                                            value="Baby">Baby</option>
                                        <option @if ($theme == 'Music') {{ 'selected' }} @endif
                                            value="Music">Music</option>
                                        <option @if ($theme == 'Cake') {{ 'selected' }} @endif
                                            value="Cake">Cake</option>
                                        <option @if ($theme == 'Parody') {{ 'selected' }} @endif
                                            value="Parody">Parody</option>
                                        <option @if ($theme == 'Tiny') {{ 'selected' }} @endif
                                            value="Tiny">Tiny</option>
                                        <option @if ($theme == 'Business') {{ 'selected' }} @endif
                                            value="Business">Business</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-secondary mr-3" style="width: 100%;">Tìm
                                        kiếm</button>
                                </div>
                                <div class="col-md-1">
                                    <a class="btn btn-outline-primary mr-3"
                                        href="{{ route('shortlink.index') }}">Reset</a>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-body table-responsive p-0">
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
                                @foreach ($shortlink as $item)
                                    <tr>
                                        <td> {{ $item->link_date }} </td>
                                        <td> {{ $item->created_by }}</td>
                                        <td> {{ $item->short_url }} </td>
                                        <td> {{ $item->long_url }} </td>
                                        <td> {{ $item->click_count }} </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.card -->
                {{ $shortlink->links() }}
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal_add_organization">
        <form method="POST" action=" {{ route('shortlink.create_bitly_api') }} ">
            @csrf
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-color1">Thêm User mới</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        {{-- <label>Id bài viết</label>
                        <input type="text" class="form-control " name="group_id" value="" required=""
                            autofocus="" placeholder="Nhập id group"> --}}
                        <label>Organization Id</label>
                        <input type="text" class="form-control " name="user_organization_guid" value=""
                            required="" autofocus="" placeholder="Nhập Organization Id" required>
                        <br>
                        <label>Access token</label>
                        <input type="text" class="form-control " name="user_access_token" value=""
                            required="" autofocus="" placeholder="Nhập user token" required>
                        {{-- <br>
                        <label>Số link</label>
                        <input type="number" class="form-control " name="link_number" value="" required=""
                            autofocus="" placeholder="Nhập số lượng links muốn lấy"> --}}
                        {{-- <br>
                        <label>Chọn tháng link khởi tạo</label>
                        <select name="month" id="" class="form-control">
                            @for ($i = 1; $i <= 12; $i++)
                                @if ($i <= 9)
                                    <option value="0{{ $i }}">Tháng {{ $i }} </option>
                                @else
                                    <option value="{{ $i }}">Tháng {{ $i }} </option>
                                @endif
                            @endfor
                        </select> --}}
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            Đóng
                        </button>
                        <button type="submit" class="btn btn-primary" id="add_user">Lưu
                            <span style="display: none;" class="spinner-border spinner-border-sm" role="status"
                                aria-hidden="true"></span>
                        </button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
        </form>
        <!-- /.modal-dialog -->
    </div>

    <div class="modal fade" id="modal_add_link_organization">
        <form method="POST" action=" {{ route('shortlink.create_bitly_link_api') }} ">
            @csrf
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-color1">Thêm bitly link mới</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        {{-- <label>Id bài viết</label>
                        <input type="text" class="form-control " name="group_id" value="" required=""
                            autofocus="" placeholder="Nhập id group"> --}}
                        <label>Organization Id</label>
                        <input type="text" class="form-control " name="organization_guid" value=""
                            required="" autofocus="" placeholder="Nhập Organization Id">
                        <br>
                        <label>Bitly link id</label>
                        <input type="text" class="form-control " name="link_id" value="" required=""
                            autofocus="" placeholder="Nhập Bitly link id">
                        <br>
                        <label>Access token</label>
                        <input type="text" class="form-control " name="user_access_token" value=""
                            required="" autofocus="" placeholder="Nhập user token">
                        {{-- <br>
                        <label>Chọn tháng link khởi tạo</label>
                        <select name="month" id="" class="form-control">
                            @for ($i = 1; $i <= 12; $i++)
                                @if ($i <= 9)
                                    <option value="0{{ $i }}">Tháng {{ $i }} </option>
                                @else
                                    <option value="{{ $i }}">Tháng {{ $i }} </option>
                                @endif
                            @endfor
                        </select> --}}
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            Đóng
                        </button>
                        <button type="submit" class="btn btn-primary" id="">Lưu
                        </button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
        </form>
        <!-- /.modal-dialog -->
    </div>
    <script>
        @php
            if (Session::has('msg')) {
                $msg = Session::get('msg');
                if (strpos($msg, 'thành công') > 0) {
                    echo 'toastr.success("' . $msg . '");';
                } elseif (strpos($msg, 'chưa có') > 0) {
                    echo 'toastr.warning("' . $msg . '");';
                } else {
                    echo 'toastr.error("' . $msg . '");';
                }
            }
        @endphp
        $(document).ready(function() {
            $('#clicks').on('click', function() {
                toastr.info('Đang tiến hành update lại lượt click của các bitlink hiện tại');
            });
        });
        $(document).ready(function() {
            $('#bitlink').on('click', function() {
                toastr.info('Đang tiến hành lấy các bitlink mới');
            });
        });
        $(document).ready(function() {
            $('#add_user').on('click', function() {
                toastr.info('Đang tiến hành lấy user bitlink');
            });
        });
        // $(document).ready(function() {
        //     $('#bitlink').on('click', function() {
        //         toastr.info('Đang tiến hành lấy các bitlink mới nhất');
        //     });
        // });
        // $('#add_user').on('click', function() {
        //     $(this).prop('disabled', true);
        //     $('#bitlink .spinner-border').show();
        // });
        $('#clicks').on('click', function() {
            $(this).prop('disabled', true);
            $('#clicks .spinner-border').show();
        });
    </script>
@endsection
