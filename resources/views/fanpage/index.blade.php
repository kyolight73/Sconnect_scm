@php

@endphp
@extends('layouts.master')
@section('content')
<style>
    .d-flex a {
        margin-right: 15px;
    }
</style>
    <div class="container">
        {!! App\Utils::createBreadcrumb(array('Marketing', 'Fanpage')) !!}
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Danh sách các fanpage</h5>
                        <div class="d-flex">
                            <a id="insert" class="btn btn-info" href=" {{ route('api.face') }} ">Get API
                                <span style="display: none;" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            </a>
                            <a class="btn btn-outline-primary" href=" {{ route('fanpage.page_insight') }} ">Đồ thị</a>
                        </div>

                        <p class="status-info">Dữ liệu được cập nhật vào lúc {{ $update_at->updated_at ?? ''}} </p>
                        <form action="" method="GET">
                            <div class="row">
                                <div class="col-md-5">
                                    <input type="text" name="name" class="form-control" placeholder="Tìm kiếm Tên Page hoặc Id">
                                </div>
                                <div class="col-md-5">
                                    <select name="filter" class="form-control" style="" onchange="this.form.submit()">
                                        <option @if ($filter == 'like_desc') {{ 'selected' }} @endif
                                            value="like_desc">
                                            Like nhiều nhất</option>
                                        <option @if ($filter == 'like_asc') {{ 'selected' }} @endif
                                            value="like_asc">
                                            Like ít nhất</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-secondary" style="width: 100%;">Tìm kiếm</button>
                                </div>
                            </div>

                        </form>
                        <div id="fb-root"></div>

                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover" border="0">
                            <thead>
                                <tr style="background-color: #dee2e6">
                                    <th>Tên Page</th>
                                    <th>Chủ đề</th>
                                    <th>Link</th>
                                    {{-- <th>Followers</th> --}}
                                    <th>Likes</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($fanpage as $item)
                                    <tr>
                                        <td><img class="thumb" src="{{ $item->picture }}" alt="{{ $item->page_name }}"> {{ $item->page_name }}</td>
                                        <td> {{ $item->page_theme }} </td>
                                        <td> {{ $item->page_url }} </td>
                                        {{-- <td> {{ $page_info->followers_count }} </td> --}}
                                        <td> {{ number_format($item->likes_count) }} lượt </td>
                                        <td>
                                            <a class="btn btn-success"
                                                href=" {{ route('fanpage.show_post_info', ['fanpage' => $item->page_id, 'access_token' => $item->access_token, 'page_name' => $item->page_name]) }} ">Bài
                                                viết</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.card -->
                {{{ $fanpage->links() }}}
            </div>
        </div>
    </div>
    <script>
        @php
	if (Session::has('msg')) {
		$msg = Session::get('msg');
		if (strpos($msg, 'thành công') > 0) {
			echo 'toastr.success("'.$msg.'");';
		} else {
			echo 'toastr.error("'.$msg.'");';
		}
	}
	@endphp
        $(document).ready(function () {
            $('#insert').on('click', function() {
                toastr.info('Đang tiến hành lấy dữ liệu các trang Fanpage');
            });
        });
    </script>
@endsection
