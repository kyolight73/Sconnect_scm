@php

    @endphp
@extends('layouts.master')
@section('content')
    <div class="container">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><i class="fas fa-home" aria-hidden="true"></i> Hệ thống</li>
            <li class="breadcrumb-item">Phân quyền</li>
        </ul>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Danh sách các nhóm</h5>
                        <div class="row" style="padding-top: 30px;margin-bottom: 10px">
                            <div class="col-sm-4">
{{--								<a href="{{ route('permissions.create')}}" class="cursor-hand btn-round text-decoration-none" style="font-size: 14px"><i class="fas fa-plus"></i> Permission</a>--}}
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
                                                    <textarea id="display_name" type="text" class="form-control @error('display_name') is-invalid @enderror" name="display_name" required placeholder="Mô tả tên nhóm">{{ old('display_name') }}</textarea>
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
                                <th style="width: 5%">#</th>
                                <th style="width: 15%">Tên Nhóm</th>
                                <th>Mô tả</th>
                                <th class="text-center" style="width: 40%">Acition</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if ($roles->count() != 0)
                            @foreach($roles as $role)

                                <tr>
                                    <th scope="row" style="width: 5%">{{ $role->id}}</th>
                                    <td style="width: 15%">{{ $role->name ?? ''}}</td>
                                    <td>{{ $role->display_name ?? ''}}</td>
                                    <td class="text-right" style="width: 40%">
                                        <a href="{{ route('roles.edit', ['id' => $role->id]) }}" class="btn btn-default cursor-hand text-decoration-none" style="font-size: 14px;padding-bottom: 0"><i class="fas fa-user-cog ic24"></i>Phân quyền</a>
                                        <a class="btn btn-default cursor-hand text-decoration-none" style="font-size: 14px;padding-bottom: 0" data-toggle="modal" data-target="#modal-edit-dept" data-name="{{$role->name}}" data-displayname="{{$role->display_name}}" data-deptid="{{$role->id}}"><i class="far fa-edit ic24"></i>Sửa nhóm</a>

                                        <a  class="btn btn-default cursor-hand text-decoration-none" data-toggle="modal" data-target="#modal-delete-roles" data-deptname="{{$role->name}}" data-deptid="{{$role->id}}" style="font-size: 14px;padding-bottom: 0"><i class="far fa-trash-alt ic24" style="color:#ff5648!important"></i>Xoá nhóm</a>
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

                        <!--  Model edit -->
                        <div class="modal fade" id="modal-edit-dept">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title text-color1">Sửa thông tin nhóm</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" id="dept-id" value="0" />
                                        <div class="row margin-top">
                                            <div class="col-12 input-group">
                                                <input id="role-name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="" required placeholder="Nhập tên nhóm"/>
                                            </div>
                                        </div>
                                        <div class="row margin-top">
                                            <div class="col-12 input-group">
                                                <textarea id="role-display-name" type="text" class="form-control @error('display_name') is-invalid @enderror" name="display_name" required placeholder="Mô tả tên nhóm"></textarea>
                                            </div>
                                        </div>
                                    <div class="modal-footer justify-content-between">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                                        <button type="button" class="btn btn-primary" id="btn-update-dept">Lưu thay đổi</button>
                                    </div>
                                </div>
                                <!-- /.modal-content -->
                            </div>
                            <!-- /.modal-dialog -->
                        </div>

                </div>
                        <!--  Model delete-->
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

            $('#modal-edit-dept').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var modal = $(this);
                modal.find('#role-name').val(button.data('name'));
                modal.find('#role-display-name').val(button.data('displayname'));
                modal.find('#dept-id').val(button.data('deptid'));

            });

            var deptId = 0;
            $('#btn-update-dept').on('click', function() {
                var modal = $('#modal-edit-dept');
                // update department ...
                var dept_id = modal.find('#dept-id').val();
                var name = modal.find('#role-name').val();
                var display_name = modal.find('#role-display-name').val();

                /* jquery post data	*/
                $.ajax({
                    url: "/roles/change-" + deptId,
                    method: "POST",
                    data: {id: dept_id, 'name': name, 'display_name': display_name},
                    headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                    success: function (jsResult) {
                        if (jsResult.status === 'success') {
                            toastr.success(jsResult.message);
                            $('#department-structure').html(jsResult.body);
                            modal.modal('hide');
                        } else {
                            toastr.error(jsResult.message);
                        }
                        location.reload();
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
