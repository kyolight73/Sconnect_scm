@extends('layouts.master')
@section('content')
    <div class="container">
        {!! App\Utils::createBreadcrumb(['Marketing', 'Group']) !!}
        <div class="row">

            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Danh sách các Group</h5>
                        <p class="status-info">Dữ liệu được cập nhật vào lúc {{ $update_at->updated_at ?? '' }} </p>
                        <div class="row" style="align-items: center;">
                            <div class="col-md-4" style="margin-top: 10px;">
                                <span class="cursor-hand btn-round" data-toggle="modal" data-target="#modal_add_group"
                                    data-whatever="" data-whatid="">
                                    <i class="fas fa-plus"></i> Thêm nhóm mới</span>
                            </div>
                            <div class="col-sm-8" align="right">
                                <a id="insert" class="btn btn-info" href="{{ route('group.get_api_all_group') }}">
                                    Get API
                                    <span style="display: none;" class="spinner-border spinner-border-sm" role="status"
                                        aria-hidden="true"></span>
                                </a>
                                <a class="btn btn-outline-primary" href="{{ route('group.group_insight') }}">Đồ thị</a>
                            </div>
                        </div>
                        <form action="" method="GET">
                            <div class="row mt-3">
                                <div class="col-md-5">
                                    <input type="text" name="name" class="form-control"
                                        placeholder="Tìm kiếm Tên Page hoặc Id">
                                </div>
                                <div class="col-md-5">
                                    <select name="filter" class="form-control" style=""
                                        onchange="this.form.submit()">
                                        <option @if ($filter == 'member_count_desc') {{ 'selected' }} @endif
                                            value="member_count_desc">
                                            Follower nhiều nhất</option>
                                        <option @if ($filter == 'member_count_asc') {{ 'selected' }} @endif
                                            value="member_count_asc">
                                            Follower ít nhất</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-secondary" style="width: 100%;">Tìm kiếm</button>
                                </div>
                            </div>

                        </form>

                        {{-- <p>Dữ liệu được cập nhật vào lúc {{ $fanpage[0]->updated_at }} </p> --}}

                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover" border="0">
                            <thead>
                                <tr style="background-color: #dee2e6">
                                    <th>Tên Group</th>
                                    <th>Địa chỉ URL</th>
                                    <th>Lượng Followers</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($group as $item)
                                    <tr>
                                        <td><img class="thumb" src="{{ $item->picture }}" alt="{{ $item->name }}">
                                            {{ $item->name }}
                                        </td>
                                        <td> {{ $item->link }} </td>
                                        <td> {{ number_format($item->member_count) }} Lượt</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
                <!-- /.card -->
                {{ $group->links() }}

            </div>

        </div>
    </div>

    <div class="modal fade" id="modal_add_group">
        <form method="POST" action=" {{ route('group.store') }} ">
            @csrf
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-color1">Thêm nhóm mới</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        {{-- <label>Id bài viết</label>
                        <input type="text" class="form-control " name="group_id" value="" required=""
                            autofocus="" placeholder="Nhập id group"> --}}
                        <label>Url nhóm</label>
                        <input type="text" class="form-control " name="link" value="" required=""
                            autofocus="" placeholder="Nhập url group">
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
                } else {
                    echo 'toastr.error("' . $msg . '");';
                }
            }
        @endphp
        $(document).ready(function() {
            $('#insert').on('click', function() {
                toastr.info('Đang tiến hành lấy dữ liệu các Group');
            });
        });
    </script>
@endsection
