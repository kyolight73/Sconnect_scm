<?php
alert("Không có quyền đâu vào làm cái rì?");

function alert($msg) {
    echo "<script type='text/javascript' >alert('$msg')</script>";
}
?>

@extends('layouts.master')
@section('content')
    <div class="wrapper">
        <div class="row justify-content-center">
            <div class="col-xs-12">Home Page</div>
        </div>
    </div>
@endsection
