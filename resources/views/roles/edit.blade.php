
@extends('layouts.master')
@section('content')
    <div class="container">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><i class="fas fa-home" aria-hidden="true"></i>Admin</li>
            <li class="breadcrumb-item">Roles</li>
        </ul>
        <div class="row">
            <form action="{{ route('roles.update',['id'=>$role->id]) }}" method="post" enctype="multipart/form-data" style="width: 100%">

                <div class="col-md-12">

                    @csrf
                    <div class="form-group">
                        <label>Tên Vai Trò</label>
                        <input type="text" class="form-control " name="name" placeholder="Nhập Tên Vai Trò"
                               value="{{$role->name}}">

                    </div>

                    <div class="form-group">
                        <label>Mô Tả Vai Trò</label>
                        <textarea class="form-control " name="display_name" placeholder="Nhập mô tả">{{$role->display_name}}</textarea>
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
                                <div class="card-header text-color2 bold">
                                    <label>
                                        <input type="checkbox" value="" class="checkbox_wrapper ">
                                    </label>
                                    Module {{$permissionsParentItem->name}}
                                </div>

                                <div class="row">
                                    @foreach($permissionsParentItem-> permissionsChildrent as $permissionsChildrentItem)
                                        <div class="card-body col-md-3">
                                            <h5 class="card-title">
                                                <label>
                                                    <input type="checkbox" name="permission_id[]"
                                                           {{$permissionsChecked->contains('id', $permissionsChildrentItem->id) ? 'checked' : ''}}
                                                           class="checkbox_childrent "
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

                <button type="submit" class="btn btn-primary">Submit</button>

            </form>

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

