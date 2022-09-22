@php

    @endphp
@extends('layouts.master')
@section('content')
    <div class="container">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><i class="fas fa-home" aria-hidden="true"></i> Admin</li>
            <li class="breadcrumb-item">Nhóm</li>
        </ul>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Danh sách các nhóm</h5>
                        <div class="row" style="padding-top: 30px;margin-bottom: 10px">
                            <div class="col-sm-4 heading3 bold">
                                {{--                                    <a href="{{ route('permissions.create')}}" class="btn btn-default">Permission</a>--}}
                            </div>
                            <div class="col-sm-8" align="right">
							<span class="cursor-hand btn-round" data-toggle="modal" data-target="#modal-add-roles"
                                  data-whatever="" data-whatid="">
								<i class="fas fa-plus"></i> Thêm nhóm</span>
                            </div>
                        </div>
                        <div class="modal fade" id="modal-add-roles">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title text-color1">Thêm nhóm mới</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                    </div>
                                    <form method="POST" action="#">@csrf
                                        <div class="modal-body">
                                            <input type="hidden" id="dept-id" value="0"/>
                                            <input type="hidden" id="staff-id" value="0"/>
                                            <div class="row margin-top">
                                                <div class="col-12 input-group">
                                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required placeholder="Nhập tên nhóm"/>
                                                </div>
                                            </div>
                                            <div class="row margin-top">
                                                <div class="col-12 input-group">
                                                    <input id="display_name" type="text" class="form-control @error('display_name') is-invalid @enderror" name="display_name" value="{{ old('display_name') }}" required placeholder="Mô tả tên nhóm"/>
                                                </div>
                                            </div>
                                            <div class="modal-footer justify-content-between">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">
                                                    Đóng
                                                </button>
                                                <button type="button" class="btn btn-primary" id="btn-save-roles">Lưu
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <!-- /.modal-content -->
                            </div>
                            <!-- /.modal-dialog -->
                        </div>
                        <div id="fb-root"></div>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover" border="0">
                            <thead>
                            <tr style="background-color: #dee2e6">
                                <th>#</th>
                                <th>Tên Nhóm</th>
                                <th>Mô tả</th>
                                <th class="text-center">Acition</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if ($roles->count() != 0)
                            @foreach($roles as $role)

                                <tr>
                                    <th scope="row">{{ $role->id}}</th>
                                    <td>{{ $role->name ?? ''}}</td>
                                    <td>{{ $role->display_name ?? ''}}</td>
                                    <td class="text-center">
                                        <a href="{{ route('roles.edit', ['id' => $role->id]) }}" class="btn btn-default cursor-hand text-decoration-none" style="font-size: 14px"><i class="far fa-edit ic24"></i>Phân quyền</a>

                                        <a  class="btn btn-default cursor-hand text-decoration-none" id="delete-roles-id" data-toggle="modal" data-target="#modal-delete-roles" data-deptname="{{$role->name}}" data-deptid="{{$role->id}}" style="font-size: 14px"><i class="far fa-trash-alt ic24" style="color:#ff5648!important"></i>Xoá nhóm</a>
                                    </td>
                                </tr>
                            @endforeach
                            @else
                                <tr>
                                    <center>
                                        <td colspan="5" style="text-align: center;"> Chưa có nhóm, vui lòng thêm.</td>
                                    </center>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                        <div class="modal fade" id="modal-delete-roles">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">Xoá nhóm</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p id="delete-msg" class="color-danger">...</p>
                                    </div>
                                    <div class="modal-footer justify-content-between">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                                        <button type="button" class="btn btn-danger" id="btn-delete-agree">Đồng ý xoá</button>
                                    </div>
                                </div>
                                <!-- /.modal-content -->
                            </div>
                            <!-- /.modal-dialog -->
                        </div>
                    </div>
                </div>
                <!-- /.card -->
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script lang="text/javascript">
        $(document).ready(function () {
            $('#modal-add-roles').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var recipient = button.data('whatever');
                var modal = $(this);
                modal.find('#dept-is').text(recipient);
                modal.find('#dept-id').val(button.data('whatid'));
                $('#modal-delegate').modal('hide');
            });
            $('#btn-save-roles').on('click', function () {
                var modal = $('#modal-add-roles');
                var name = modal.find('#name').val();
                var display_name = modal.find('#display_name').val();

                /* jquery post data	*/
                $.ajax({
                    url: "/roles/add",
                    method: "POST",
                    data: {
                        'name': name,
                        'display_name': display_name,
                    },
                    headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                    success: function (jsResult) {
                        if (jsResult.status === 'success') {
                            toastr.success(jsResult.message);
                            modal.modal('hide');
                            location.reload();
                        } else {
                            toastr.error(jsResult.message);
                        }
                    }
                });
                /**/
            });
            var deptIdToDelete = 0;

            $('#modal-delete-roles').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                deptIdToDelete = button.data('deptid');
                var modal = $(this);
                modal.find('#delete-msg').html("Bạn có chắc chắn xoá nhóm?");
            })
            $('#btn-delete-agree').on('click', function() {
                // delete roles ...
                $.get( "/roles/delete-" + deptIdToDelete, function( jsResult ) {
                    if (jsResult.status === 'success') {
                        toastr.success(jsResult.message);
                        $('#department-structure').html(jsResult.body);
                        location.reload();
                    } else {
                        toastr.error(jsResult.message);
                    }
                });
                $('#modal-delete-roles').modal('hide');
            });
            $('#modal-delete-roles').on('hide.bs.modal', function (event) {
                deptIdToDelete = 0;
            })
        });
    </script>
@endsection
