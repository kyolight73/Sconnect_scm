@extends('layouts.master')
@section('content')
    <div class="container">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><i class="fas fa-home" aria-hidden="true"></i>Admin</li>
            <li class="breadcrumb-item">Roles</li>
        </ul>
        <div class="row">
            <div class="col-md-12">
                <form action="{{ route('roles.update',['id'=>$role->id]) }}" method="post"
                      enctype="multipart/form-data">
                    <div class="col-md-12">
                        @csrf
                        <div class="form-group">
                            <label>Tên Nhóm: </label>
                            {{$role->name}}
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12">
                                <labe>
                                    <input type="checkbox" class="checkall">
                                    checkall
                                </labe>
                            </div>
                            @foreach($permissionsParent as $permissionsParentItem)
                                <div class="card border-primary mb-3 col-md-12">
                                    <div class="card-header text-color2 bold" style="
                                     padding-left: 12px;">
                                        <label>
                                            <input type="checkbox" value="" class="checkbox_wrapper ">
                                        </label>
                                        {{$permissionsParentItem->name}}
                                    </div>
                                    <div class="row">
                                        @foreach($permissionsParentItem-> permissionsChildrent as $permissionsChildrentItem)
                                            <div class="card-body col-md-3">
                                                <h5 class="card-title" style="font-size: 14px">
                                                    <label>
                                                        <input type="checkbox" name="permission_id[]" class="checkbox_childrent"
                                                               {{$permissionsChecked->contains('id', $permissionsChildrentItem->id)? 'checked' : ''}}
                                                               value="{{$permissionsChildrentItem->id}}">
                                                    </label>
                                                    {{$permissionsChildrentItem->name}}
                                                </h5>
                                            </div>
                                        @endforeach

                                    </div>
                                </div>
                            @endforeach
                        </div>

                    </div>
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        $(function () {
            $('.checkbox_wrapper').on('click', function () {
                $(this).parents('.card').find('.checkbox_childrent').prop('checked', $(this).prop('checked'));
            });

            $('.checkall').on('click', function () {
                $(this).parents().find('.checkbox_childrent').prop('checked', $(this).prop('checked'));
                $(this).parents().find('.checkbox_wrapper').prop('checked', $(this).prop('checked'));
            });


        });

    </script>
@endsection

