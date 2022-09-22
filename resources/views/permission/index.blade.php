@php

    @endphp
@extends('layouts.master')
@section('content')
    <div class="container">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><i class="fas fa-home" aria-hidden="true"></i> Admin</li>
            <li class="breadcrumb-item">Permission</li>
        </ul>
        <div class="row">
            <form action="{{ route('permissions.store') }}" method="post">
                @csrf

                <div class="form-group">
                    <label>Chọn Tên Module</label>
                    <select class="form-control" name="module_parent">
                        <option value="">Chọn Tên Module</option>
                        @foreach(config('permission.table_module') as $moduleItem)
                            <option value="{{$moduleItem}}">{{$moduleItem }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <div class="row">
                        @foreach(config('permission.module_childrent') as $moduleItemChildrent)
                            <div class="col-md-3">
                                <label >
                                    <input type="checkbox" value="{{$moduleItemChildrent}}" name="module_childrent[]">
                                    {{$moduleItemChildrent}}
                                </label>
                            </div>
                        @endforeach

                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
@endsection
