@extends('layouts.master')
@section('content')
    <div class="container">
        {!! App\Utils::createBreadcrumb(['Fanpage', 'Bài viết']) !!}
        <div class="row">

            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Danh sách các fanpage</h5>
                        <p class="status-info">Dữ liệu được cập nhật vào lúc {{ $update_at->updated_at ?? '' }} </p>

                        <div class="row" style="align-items: center;">
                            <div class="col-md-4" style="margin-top: 10px;">
                                <span class="cursor-hand btn-round" data-toggle="modal" data-target="#modal_add_posts"
                                    data-whatever="" data-whatid="">
                                    <i class="fas fa-plus"></i> Thêm bài viết mới</span>
                                <span class="cursor-hand btn-round" data-toggle="modal" data-target="#modal_add_posts_date"
                                    data-whatever="" data-whatid="">
                                    <i class="fas fa-plus"></i> Gọi bài viết theo thời gian</span>
                            </div>
                            <div class="col-sm-8" align="right">
                                <a class="btn btn-info" id="insert"
                                    href=" {{ route('api.get_api_all_post', ['page_id' => $page_id, 'access_token' => $at]) }} ">
                                    Get API
                                    <span style="display: none;" class="spinner-border spinner-border-sm" role="status"
                                        aria-hidden="true"></span></a>
                            </div>
                        </div>
                        <form action="" method="GET">
                            <div class="row search-holder mt-3">
                                <div class="col-lg-2">
                                    <input type="text" name="name" class="form-control"
                                        placeholder="Tìm kiếm tiêu đề bài viết hoặc Id">
                                </div>
                                <div class="col-lg-2">
                                    <input type="date" name="search_from" class="form-control"
                                        value="{{ $rq->search_from }}">
                                </div>
                                <div class="col-lg-3">
                                    <input type="date" name="search_to" class="form-control"
                                        value="{{ $rq->search_to }}">
                                </div>
                                <div class="col-lg-2">
                                    <select name="filter" class="form-control" style="">
                                        <option @if ($filter == 'date_desc') {{ 'selected' }} @endif
                                            value="date_desc">
                                            Mới nhất</option>
                                        <option @if ($filter == 'date_asc') {{ 'selected' }} @endif
                                            value="date_asc">
                                            Cũ nhất</option>
                                        <option @if ($filter == 'interact_desc') {{ 'selected' }} @endif
                                            value="interact_desc">
                                            Nhiều tương tác nhất</option>
                                        <option @if ($filter == 'interact_asc') {{ 'selected' }} @endif
                                            value="interact_asc">
                                            Ít tương tác nhất</option>
                                    </select>
                                </div>
                                <div class="col-lg-2">
                                    <button type="submit" class="btn btn-secondary" style="width: 100%;">Lọc</button>
                                </div>
                                <div class="col-lg-1">
                                    <a href="{{ route('fanpage.show_post_info', ['fanpage' => $page_id]) }}"
                                        style="width: 100%;" class="btn btn-outline-primary">Reset</a>
                                </div>
                            </div>

                        </form>
                        {{-- <p>Dữ liệu được cập nhật vào lúc {{ $fanpage[0]->updated_at }} </p> --}}

                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover" border="0">
                            <thead>
                                <tr style="background-color: #dee2e6">
                                    <th style="width: 500px;">Tiêu đề</th>
                                    <th>Ngày tạo</th>
                                    <th>Tương tác</th>
                                    <th><img src=" {{ asset('images/icons/like.png') }} " alt="like"></th>
                                    <th><img src=" {{ asset('images/icons/angry.png') }} " alt="angry"></th>
                                    {{-- <th>Followers</th> --}}
                                    <th><img src=" {{ asset('images/icons/sad.png') }} " alt="sad"></th>
                                    <th><img src=" {{ asset('images/icons/haha.png') }} " alt="haha"></th>
                                    <th><img src=" {{ asset('images/icons/love.png') }} " alt="love"></th>
                                    <th><img src=" {{ asset('images/icons/wow.png') }} " alt="wow"></th>
                                    <th>Chia sẻ</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($post_info as $item)
                                    <tr>
                                        <td> <a href="{{ $item->link }}" target="__blank">{{ $item->message }} </a></td>
                                        <td> {{ $item->post_create }} </td>
                                        <td>
                                            {{ $item->like_count + $item->angry_count + $item->sad_count + $item->haha_count + $item->love_count + $item->wow_count }}
                                        </td>
                                        <td> {{ $item->like_count }} </td>
                                        <td> {{ $item->angry_count }} </td>
                                        <td> {{ $item->sad_count }} </td>
                                        <td> {{ $item->haha_count }} </td>
                                        <td> {{ $item->love_count }} </td>
                                        <td> {{ $item->wow_count }} </td>
                                        <td> {{ $item->shares_count }} </td>
                                        <td>
                                            <form action="{{ route('post_info.delete', ['post_info' => $item->id]) }}"
                                                method="post">
                                                <button type="submit" style="border: 0; background: transparent;">
                                                    <i class="far fa-trash-alt cursor-hand" style="color: #ff5648"
                                                        aria-hidden="true"></i>
                                                </button>
                                                <input type="hidden" name="_method" value="delete" />
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.card -->
                {{ $post_info->links() }}
            </div>

        </div>
    </div>

    <div class="modal fade" id="modal_add_posts">
        <form method="POST"
            action="{{ route('fanpage.create_post_info', ['fanpage' => $page_id, 'access_token' => $at]) }}">
            @csrf
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-color1">Thêm bài viết mới</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <label>Id bài viết</label>
                        <input type="text" class="form-control " name="post_id" value="" required=""
                            autofocus="" placeholder="Nhập id bài viết">
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            Đóng
                        </button>
                        <button type="submit" class="btn btn-primary" id="call">Lưu
                            <span style="display: none;" class="spinner-border spinner-border-sm" role="status"
                                aria-hidden="true"></span></a>
                        </button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
        </form>
        <!-- /.modal-dialog -->
    </div>

    <div class="modal fade" id="modal_add_posts_date">
        <form method="POST"
            action="{{ route('fanpage.add_post_api_from_to', ['fanpage' => $page_id, 'access_token' => $at]) }}">
            @csrf
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-color1">Thêm bài viết mới</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <label>Từ</label>
                        <input type="date" class="form-control" name="from">
                        <label>Tới</label>
                        <input type="date" class="form-control" name="to">
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            Đóng
                        </button>
                        <button type="submit" class="btn btn-primary" id="call_from">Lưu
                            <span style="display: none;" class="spinner-border spinner-border-sm" role="status"
                                aria-hidden="true"></span></a>
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
            $('#call_from').on('click', function() {
                toastr.info('Đang tiến hành lấy dữ liệu các bài viết từ Fanpage');
            });
        });
        $(document).ready(function() {
            $('#call').on('click', function() {
                toastr.info('Đang tiến hành lấy dữ liệu các bài viết từ Fanpage');
            });
        });
        $(document).ready(function() {
            $('#insert').on('click', function() {
                toastr.info('Đang tiến hành lấy dữ liệu các bài viết từ Fanpage');
            });
        });
    </script>
@endsection
